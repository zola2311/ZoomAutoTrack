<?php

namespace App\Observers;

use App\Http\Controllers\Portal\AppointmentController;
use App\Models\Appointment;
use App\Models\JobCard;
use App\Notifications\AppointmentConfirmed;

class AppointmentObserver
{
    public function created(Appointment $appointment): void
    {
        $this->maybeCreateJobCard($appointment);
    }

    public function updated(Appointment $appointment): void
    {
        if ($appointment->wasChanged('status')) {
            $this->maybeCreateJobCard($appointment);
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
