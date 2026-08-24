<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function dashboard(): View
    {
        $customer = Auth::guard('customer')->user();

        $vehicles = $customer->vehicles()->orderBy('plate_number')->get();

        return view('portal.dashboard', [
            'customer' => $customer,
            'vehicles' => $vehicles,
        ]);
    }

    public function history(): View
    {
        $customer = Auth::guard('customer')->user();

        $jobCards = $customer->jobCards()
            ->with('vehicle')
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->paginate(15);

        return view('portal.history', [
            'jobCards' => $jobCards,
        ]);
    }
}
