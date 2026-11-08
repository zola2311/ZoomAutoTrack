<?php

namespace App\Observers;

use App\Http\Controllers\Portal\AppointmentController;
use App\Models\Appointment;
use App\Models\JobCard;
use App\Models\User;
use App\Notifications\AppointmentConfirmed;
use App\Notifications\NewBookingStaffAlert;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AppointmentObserver
{
    public function created(Appointment $appointment): void
    {
        $this->notifyStaffOfNewBooking($appointment);
        $this->maybeCreateJobCard($appointment);
    }

    public function updated(Appointment $appointment): void
    {
        if ($appointment->wasChanged('status')) {
            $this->maybeCreateJobCard($appointment);
        }
    }

    /**
     * Notify workshop staff (Service Advisor, Receptionist, Manager, Admin)
     * via Dashboard Bell Icon (database notification) and Email when a new booking arrives.
     */
    protected function notifyStaffOfNewBooking(Appointment $appointment): void
    {
        try {
            $appointment->loadMissing(['customer', 'vehicle', 'branch']);

            $customer = $appointment->customer;
            $vehicle = $appointment->vehicle;
            $dateStr = $appointment->requested_date ? $appointment->requested_date->format('M d, Y') : 'Upcoming';

            // 1. Send Instant Dashboard Notification (Bell Icon 🔔)
            $staffUsers = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin', 'manager', 'service_advisor', 'receptionist']);
            })->where('is_active', true)->get();

            if ($staffUsers->isNotEmpty()) {
                $notificationData = \Filament\Notifications\Notification::make()
                    ->title('New Service Booking')
                    ->icon('heroicon-o-calendar-days')
                    ->iconColor('warning')
                    ->body("{$customer?->full_name} booked {$dateStr} for {$vehicle?->plate_number} ({$vehicle?->make} {$vehicle?->model}).")
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->url('/admin/appointments'),
                    ])
                    ->getDatabaseMessage();

                foreach ($staffUsers as $staff) {
                    DB::table('notifications')->insert([
                        'id' => (string) Str::uuid(),
                        'type' => \Filament\Notifications\DatabaseNotification::class,
                        'notifiable_type' => User::class,
                        'notifiable_id' => $staff->id,
                        'data' => json_encode($notificationData),
                        'read_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 2. Send Email Notification to designated staff / admin emails
            $targetEmails = array_filter(array_unique([
                config('mail.admin_notification_email'),
                User::whereHas('roles', fn ($q) => $q->where('name', 'admin'))->first()?->email,
            ]));

            foreach ($targetEmails as $email) {
                try {
                    Notification::route('mail', $email)
                        ->notify(new NewBookingStaffAlert($appointment));
                } catch (\Throwable $e) {
                    Log::warning("Could not deliver booking email to {$email}: " . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error notifying staff of new appointment: ' . $e->getMessage());
        }
    }

    protected function maybeCreateJobCard(Appointment $appointment): void
    {
        if ($appointment->status !== 'confirmed' || $appointment->job_card_id) {
            return;
        }

        $checkInTime = $appointment->requested_date->copy();
        $checkInTime = match ($appointment->requested_time_slot) {
            'morning' => $checkInTime->setTime(9, 0),
            'afternoon' => $checkInTime->setTime(14, 0),
            default => $checkInTime->startOfDay(),
        };

        $jobCard = JobCard::create([
            'branch_id' => $appointment->branch_id,
            'customer_id' => $appointment->customer_id,
            'vehicle_id' => $appointment->vehicle_id,
            'mileage_at_checkin' => $appointment->vehicle?->current_mileage,
            'checked_in_at' => $checkInTime,
            'customer_complaint' => collect($appointment->service_types ?? [])
                    ->map(fn ($t) => AppointmentController::SERVICE_TYPES[$t] ?? $t)
                    ->join(', ')
                .($appointment->other_service_description ? ' — '.$appointment->other_service_description : '')
                .($appointment->notes ? "\n\nNotes: ".$appointment->notes : ''),
            'status' => 'pending',
        ]);

        // Update quietly — avoids re-firing this observer's updated() hook.
        $appointment->timestamps = false;
        $appointment->job_card_id = $jobCard->id;
        $appointment->saveQuietly();

        if ($appointment->customer && $appointment->customer->email) {
            $appointment->customer->notify(new AppointmentConfirmed($appointment->fresh(['vehicle', 'jobCard'])));
        }
    }
}
