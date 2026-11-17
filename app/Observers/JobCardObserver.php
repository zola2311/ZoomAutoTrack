<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\JobCard;
use App\Models\User;
use App\Notifications\InvoiceGeneratedStaffAlert;
use App\Notifications\JobCardAssignedNotification;
use App\Notifications\VehicleReadyForPickup;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Log;
class JobCardObserver
{
    public function created(JobCard $jobCard): void
    {
        $this->syncVehicleMileage($jobCard);

        if ($jobCard->mechanic_id) {
            $this->notifyMechanicAssigned($jobCard);
        }
    }
    public function updated(JobCard $jobCard): void
    {
        if ($jobCard->wasChanged('mileage_at_checkin')) {
            $this->syncVehicleMileage($jobCard);
        }

        if ($jobCard->wasChanged('mechanic_id') && $jobCard->mechanic_id) {
            $this->notifyMechanicAssigned($jobCard);
        }

        // Only trigger when status changes to completed
        if (! $jobCard->wasChanged('status')) {
            return;
        }

        if ($jobCard->status !== 'completed') {
            return;
        }
        if ($jobCard->customer && ! $jobCard->customer->password && $jobCard->customer->email) {
            try {
                $token = \Illuminate\Support\Facades\Password::broker('customers')->createToken($jobCard->customer);
                $jobCard->customer->sendPasswordResetNotification($token);
            } catch (\Throwable $e) {
                Log::warning("Could not dispatch customer reset notification: " . $e->getMessage());
            }
        }
        if ($jobCard->customer && $jobCard->customer->email) {
            try {
                $jobCard->customer->notify(new VehicleReadyForPickup($jobCard));
            } catch (\Throwable $e) {
                Log::warning("Could not dispatch pickup notification to {$jobCard->customer->email}: " . $e->getMessage());
            }
        }
        $this->maybeAwardReferralBonus($jobCard);
        // Don't create a duplicate invoice if one already exists
        if (Invoice::where('job_card_id', $jobCard->id)->exists()) {
            return;
        }

        $jobCard->loadMissing(['services', 'partsUsed.inventoryItem']);

        // Build invoice items from services (labor)
        $items = [];

        foreach ($jobCard->services as $service) {
            $items[] = [
                'item_type'   => 'labor',
                'description' => $service->description,
                'quantity'    => 1,
                'unit_price'  => $service->labor_cost,
                'discount'    => 0,
                'tax'         => 0,
                'total'       => $service->labor_cost,
            ];
        }

        // Build invoice items from parts used
        foreach ($jobCard->partsUsed as $part) {
            $items[] = [
                'item_type'   => 'part',
                'description' => optional($part->inventoryItem)->name ?? 'Part',
                'quantity'    => $part->quantity,
                'unit_price'  => $part->unit_price,
                'discount'    => $part->discount,
                'tax'         => 0,
                'total'       => $part->total,
            ];
        }

        // Calculate totals
        $subtotal = collect($items)->sum('total');
        $total    = $subtotal; // no invoice-level tax/discount at creation

        // Create the invoice
        $invoice = Invoice::create([
            'branch_id'      => $jobCard->branch_id,
            'job_card_id'    => $jobCard->id,
            'customer_id'    => $jobCard->customer_id,
            'subtotal'       => $subtotal,
            'discount'       => 0,
            'tax'            => 0,
            'total'          => $total,
            'paid_amount'    => 0,
            'balance'        => $total,
            'status'         => 'unpaid',
            'issued_at'      => now(),
        ]);

        // Create line items
        foreach ($items as $item) {
            InvoiceItem::create(array_merge($item, [
                'invoice_id' => $invoice->id,
            ]));
        }

        // Notify Cashiers & Finance staff of new invoice
        $this->notifyCashiersOfInvoice($invoice);
    }
    protected function syncVehicleMileage(JobCard $jobCard): void
    {
        $vehicle = $jobCard->vehicle;

        if ($vehicle && $jobCard->mileage_at_checkin > $vehicle->current_mileage) {
            $vehicle->update([
                'current_mileage' => $jobCard->mileage_at_checkin,
                'last_service_date' => $jobCard->checked_in_at ?? now(),
            ]);
        }
    }
    protected function maybeAwardReferralBonus(JobCard $jobCard): void
    {
        // 500-point bonus to both sides, awarded once, on the referred
        // customer's very first completed job.


        $customer = $jobCard->customer;

        if (! $customer || ! $customer->referred_by_customer_id || $customer->referral_bonus_awarded_at) {
            return;
        }

        $isFirstCompletedJob = JobCard::where('customer_id', $customer->id)
            ->where('status', 'completed')
            ->where('id', '!=', $jobCard->id)
            ->doesntExist();

        if (! $isFirstCompletedJob) {
            return;
        }

        $referrer = $customer->referredBy;

        if (! $referrer) {
            return;
        }

        $customer->increment('loyalty_points', 500);
        $referrer->increment('loyalty_points', 500);

        $customer->update(['referral_bonus_awarded_at' => now()]);
    }

    /**
     * Notify mechanic via Dashboard Bell Icon and Email when a work order is assigned or delegated.
     */
    protected function notifyMechanicAssigned(JobCard $jobCard): void
    {
        try {
            $jobCard->loadMissing(['mechanic', 'vehicle', 'customer']);
            $mechanic = $jobCard->mechanic;

            if (! $mechanic) {
                return;
            }

            $vehicle = $jobCard->vehicle;
            $priority = strtoupper($jobCard->priority ?: 'Normal');

            // 1. Dashboard Bell Icon Notification for Mechanic
            FilamentNotification::make()
                ->title("Work Order Assigned: #{$jobCard->job_number}")
                ->icon('heroicon-o-wrench-screwdriver')
                ->iconColor('warning')
                ->body("Assigned vehicle {$vehicle?->plate_number} ({$vehicle?->make} {$vehicle?->model}). Priority: {$priority}")
                ->actions([
                    Action::make('view')
                        ->button()
                        ->url("/admin/job-cards/{$jobCard->id}"),
                ])
                ->sendToDatabase($mechanic);

            // 2. Email Notification to Mechanic
            if ($mechanic->email && filter_var($mechanic->email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $mechanic->notify(new JobCardAssignedNotification($jobCard));
                } catch (\Throwable $e) {
                    Log::warning("Could not dispatch mechanic job email to {$mechanic->email}: " . $e->getMessage());
                }
            }

            // 3. Notify Admin(s) that work order has been delegated
            $admins = User::whereHas('roles', fn ($q) => $q->where('name', 'admin'))
                ->where('id', '!=', $mechanic->id)
                ->where('is_active', true)
                ->get();

            if ($admins->isNotEmpty()) {
                FilamentNotification::make()
                    ->title("Job Assigned: #{$jobCard->job_number}")
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('info')
                    ->body("Delegated to {$mechanic->name} for vehicle {$vehicle?->plate_number}.")
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->url("/admin/job-cards/{$jobCard->id}"),
                    ])
                    ->sendToDatabase($admins);
            }
        } catch (\Throwable $e) {
            Log::error('Error notifying mechanic of job assignment: ' . $e->getMessage());
        }
    }

    /**
     * Notify cashiers & finance staff when a job card is completed and invoice is generated.
     */
    protected function notifyCashiersOfInvoice(Invoice $invoice): void
    {
        try {
            $invoice->loadMissing(['jobCard.vehicle', 'customer']);

            $financeUsers = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin', 'manager', 'cashier']);
            })->where('is_active', true)->get();

            if ($financeUsers->isEmpty()) {
                return;
            }

            $totalFormatted = number_format($invoice->total, 2) . ' ETB';
            $jobRef = $invoice->jobCard?->job_number ? "#{$invoice->jobCard->job_number}" : "Job #{$invoice->job_card_id}";

            // 1. Dashboard Bell Icon Notification
            FilamentNotification::make()
                ->title("Invoice Ready: {$invoice->invoice_number}")
                ->icon('heroicon-o-banknotes')
                ->iconColor('success')
                ->body("{$jobRef} complete. Total due: {$totalFormatted}")
                ->actions([
                    Action::make('collect')
                        ->button()
                        ->url("/admin/invoices/{$invoice->id}"),
                ])
                ->sendToDatabase($financeUsers);

            // 2. Email Notifications to Cashiers & Managers
            foreach ($financeUsers as $fu) {
                if ($fu->email && filter_var($fu->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        $fu->notify(new InvoiceGeneratedStaffAlert($invoice));
                    } catch (\Throwable $e) {
                        Log::warning("Could not dispatch invoice email to {$fu->email}: " . $e->getMessage());
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error notifying cashiers of new invoice: ' . $e->getMessage());
        }
    }
}
