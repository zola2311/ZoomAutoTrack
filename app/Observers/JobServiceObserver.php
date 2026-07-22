<?php

namespace App\Observers;

use App\Models\JobService;

class JobServiceObserver
{
    /**
     * Handle the JobService "created" event.
     */
    public function created(JobService $jobService): void
    {
        //
    }

    /**
     * Handle the JobService "updated" event.
     */
    public function updated(JobService $jobService): void
    {
        //
    }

    /**
     * Handle the JobService "deleted" event.
     */


    /**
     * Handle the JobService "restored" event.
     */
    public function restored(JobService $jobService): void
    {
        //
    }

    /**
     * Handle the JobService "force deleted" event.
     */
    public function forceDeleted(JobService $jobService): void
    {
        //
    }
    public function saved($model): void
    {
        $jobCard = $model->jobCard;

        // Reopen job if it was completed and new work was added
        if ($jobCard->status === 'completed') {
            $jobCard->update(['status' => 'in_progress', 'completed_at' => null]);
        }

        // Resync invoice if one exists
        $invoice = \App\Models\Invoice::where('job_card_id', $jobCard->id)->first();
        if ($invoice) {
            $invoice->resyncFromJobCard();
        }
    }

    public function deleted($model): void
    {
        $this->saved($model);
    }
}
