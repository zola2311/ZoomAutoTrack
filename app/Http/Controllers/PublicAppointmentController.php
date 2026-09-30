<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Portal\AppointmentController as PortalAppointmentController;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

/**
 * Guest-facing "book a service" flow — no customer login required.
 *
 * A Customer + Vehicle record is found-or-created from what the guest
 * submits (matched on phone number, which is the unique key on the
 * customers table). Creating an account is entirely optional here:
 * if the guest doesn't set a password now, one gets emailed to them
 * automatically once their job card is marked completed — see
 * JobCardObserver::updated().
 */
class PublicAppointmentController extends Controller
{
    public function create(): View
    {
        return view('site.book', [
            'serviceTypes' => PortalAppointmentController::SERVICE_TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],

            'plate_number' => ['required', 'string', 'max:30'],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1980', 'max:' . (date('Y') + 1)],
            'fuel_type' => ['nullable', 'string', 'in:petrol,diesel,hybrid,electric'],

            'requested_date' => ['required', 'date', 'after_or_equal:today'],
            'requested_time_slot' => ['nullable', 'string', 'max:50'],
            'service_types' => ['required', 'array', 'min:1'],
            'service_types.*' => ['string', 'in:' . implode(',', array_keys(PortalAppointmentController::SERVICE_TYPES))],
            'other_service' => ['nullable', 'string', 'max:500', 'required_if:service_types.*,other'],
            'notes' => ['nullable', 'string', 'max:1000'],

            'want_account' => ['nullable', 'boolean'],
            'password' => ['nullable', 'required_if:want_account,1', 'confirmed', PasswordRule::defaults()],
        ]);

        if (in_array('other', $data['service_types']) && empty($data['other_service'])) {
            return back()->withInput()->withErrors(['other_service' => 'Please describe the "Other" service you need.']);
        }

        // Single-branch for now — swap for a branch picker once you have more than one.
        $branch = Branch::where('is_active', true)->first() ?? Branch::first();

        // Match an existing guest/customer by phone (the unique, non-nullable
        // contact field) so repeat visitors don't get duplicate records.
        $customer = Customer::withTrashed()->where('phone', $data['phone'])->first();

        if ($customer && $customer->trashed()) {
            $customer->restore();
        }

        if (! $customer) {
            $customer = Customer::create([
                'branch_id' => $branch->id,
                'full_name' => $data['full_name'],
                'phone' => $data['phone'],
                'email' => $data['email'] ?? null,
                'type' => 'individual',
            ]);
        } else {
            // Keep the record current, but never clobber an email a returning
            // customer already has on file with a blank one.
            $customer->fill([
                'full_name' => $data['full_name'],
                'email' => $data['email'] ?? $customer->email,
            ])->save();
        }

        $vehicle = Vehicle::withTrashed()
            ->where('customer_id', $customer->id)
            ->where('plate_number', $data['plate_number'])
            ->first();

        if ($vehicle && $vehicle->trashed()) {
            $vehicle->restore();
        }

        if (! $vehicle) {
            $vehicle = Vehicle::create([
                'branch_id' => $branch->id,
                'customer_id' => $customer->id,
                'plate_number' => $data['plate_number'],
                'make' => $data['make'],
                'model' => $data['model'],
                'year' => $data['year'] ?? null,
                'fuel_type' => $data['fuel_type'] ?? null,
            ]);
        }

        Appointment::create([
            'branch_id' => $branch->id,
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'requested_date' => $data['requested_date'],
            'requested_time_slot' => $data['requested_time_slot'] ?? null,
            'service_types' => $data['service_types'],
            'other_service_description' => $data['other_service'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'source' => 'website',
        ]);

        $statusMessage = 'Thanks — your appointment request has been sent. We\'ll confirm it soon.';

        // Optional signup, right now.
        if (! empty($data['want_account']) && ! empty($data['password'])) {
            $customer->forceFill(['password' => $data['password']])->save();

            if ($customer->email) {
                $customer->sendEmailVerificationNotification();
                $statusMessage .= ' We\'ve also sent a verification email so you can log in and track this appointment.';
            }
        } elseif ($customer->email) {
            $statusMessage .= ' Once your service is complete, we\'ll email you login details so you can view your full vehicle history anytime.';
        }

        return redirect()
            ->route('booking.create')
            ->with('status', $statusMessage);
    }
}
