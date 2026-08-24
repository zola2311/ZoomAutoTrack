<?php

namespace App\Observers;


use App\Models\PartUsed;
class PartsUsedObserver
{
    /**
     * Handle the PartsUsed "created" event.
     */
    public function created(PartUsed $partsUsed): void
    {
        //
    }

    /**
     * Handle the PartsUsed "updated" event.
     */
    public function updated(PartUsed $partsUsed): void
    {
        //
    }

    /**
     * Handle the PartsUsed "deleted" event.
     */


    /**
     * Handle the PartsUsed "restored" event.
     */
    public function restored(PartUsed $partsUsed): void
    {
        //
    }

    /**
     * Handle the PartsUsed "force deleted" event.
     */
    public function forceDeleted(PartUsed $partsUsed): void
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
