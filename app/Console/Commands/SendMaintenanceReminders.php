<?php

namespace App\Console\Commands;

use App\Models\Vehicle;
use App\Notifications\MaintenanceDueReminder;
use Illuminate\Console\Command;

class SendMaintenanceReminders extends Command
{
    protected $signature = 'reminders:maintenance';

    protected $description = 'Email customers whose vehicles are due or approaching their next service';

    public function handle(): void
    {
        $sent = 0;

        $vehicles = Vehicle::with('customer')->whereNotNull('customer_id')->get();

        $this->info("Found {$vehicles->count()} vehicles with a customer.");

        foreach ($vehicles as $vehicle) {
            $this->line("Checking {$vehicle->plate_number}...");

            if (! $vehicle->customer || ! $vehicle->customer->email) {
                $this->warn("  -> skipped: no customer email");
                continue;
            }

            $due = $vehicle->nextServiceDue();
            $this->line("  -> is_due: " . ($due['is_due'] ? 'yes' : 'no') . ", km_remaining: {$due['km_remaining']}");

            if (! $due['is_due'] && $due['km_remaining'] > 500) {
                $this->warn("  -> skipped: not due soon");
                continue;
            }

            if ($vehicle->last_maintenance_reminder_sent_at
                && $vehicle->last_maintenance_reminder_sent_at->gt(now()->subDays(7))) {
                $this->warn("  -> skipped: reminded within 7 days");
                continue;
            }

            try {
                $vehicle->customer->notify(new \App\Notifications\MaintenanceDueReminder($vehicle, $due));
                $vehicle->update(['last_maintenance_reminder_sent_at' => now()]);
                $this->info("  -> SENT to {$vehicle->customer->email}");
                $sent++;
            } catch (\Throwable $e) {
                $this->error("  -> FAILED: " . $e->getMessage());
            }
        }

        $this->info("Sent {$sent} maintenance reminder(s).");
    }
}
