<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\JobCard;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PrintController extends Controller
{
    public function invoice(Invoice $invoice): View
    {
        Gate::authorize('view', $invoice);

        $invoice->load(['items', 'customer', 'branch', 'jobCard.vehicle']);

        return view('print.invoice', ['invoice' => $invoice]);
    }

    public function jobCard(JobCard $jobCard): View
    {
        Gate::authorize('view', $jobCard);

        $jobCard->load(['customer', 'vehicle', 'branch', 'services', 'partsUsed.inventoryItem', 'mechanic']);

        return view('print.job-card', ['jobCard' => $jobCard]);
    }
}
