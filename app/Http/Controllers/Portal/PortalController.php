<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function dashboard(): View
    {
        $customer = Auth::guard('customer')->user()->load(['vehicles', 'referrals']);

        $vehicles = $customer->vehicles()->orderBy('plate_number')->get();

        $totalVisits = $customer->jobCards()->whereNotNull('completed_at')->count();

        $lastVisit = $customer->jobCards()
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->first();

        $referralCount = $customer->referrals()->count();

        $recentHistory = $customer->jobCards()
            ->with('vehicle')
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->limit(3)
            ->get();

        return view('portal.dashboard', compact(
            'customer', 'vehicles', 'totalVisits',
            'lastVisit', 'referralCount', 'recentHistory'
        ));
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
