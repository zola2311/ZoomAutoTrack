<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\User;
use App\Notifications\NewCustomerStaffAlert;
use Filament\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        $this->notifyStaffOfNewCustomer($customer);
    }

    /**
     * Notify workshop staff (Receptionist, Service Advisor, Manager, Admin)
     * via Dashboard Bell Icon (database notification) and Email when a new customer registers.
     */
    protected function notifyStaffOfNewCustomer(Customer $customer): void
    {
        try {
            $customerCode = $customer->customer_code ?: 'CUST-' . str_pad($customer->id, 4, '0', STR_PAD_LEFT);

            // 1. Send Instant Dashboard Bell Icon Notification to front-desk staff & managers
            $staffUsers = User::whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin', 'manager', 'service_advisor', 'receptionist']);
            })->where('is_active', true)->get();

            if ($staffUsers->isNotEmpty()) {
                FilamentNotification::make()
                    ->title('New Customer Registered')
                    ->icon('heroicon-o-user-plus')
                    ->iconColor('info')
                    ->body("{$customer->full_name} ({$customer->phone}) registered. Client Code: {$customerCode}")
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->url("/admin/customers/{$customer->id}"),
                    ])
                    ->sendToDatabase($staffUsers);
            }

            // 2. Dispatch Staff Email Alert
            $targetEmails = [];

            // Receptionists & Service Advisors
            foreach ($staffUsers as $staff) {
                if ($staff->email && filter_var($staff->email, FILTER_VALIDATE_EMAIL)) {
                    $targetEmails[] = $staff->email;
                }
            }

            // Fallback / Admin notification email
            $adminEmail = config('mail.admin_notification_email');
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                $targetEmails[] = $adminEmail;
            }

            $uniqueEmails = array_unique($targetEmails);

            foreach ($uniqueEmails as $email) {
                try {
                    Notification::route('mail', $email)
                        ->notify(new NewCustomerStaffAlert($customer));
                } catch (\Throwable $e) {
                    Log::warning("Could not dispatch customer alert email to {$email}: " . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error notifying staff of new customer: ' . $e->getMessage());
        }
    }
}
