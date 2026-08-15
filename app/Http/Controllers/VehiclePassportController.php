<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class VehiclePassportController extends Controller
{
    /**
     * Public, no-login vehicle passport. Resolved by the vehicle's
     * qr_code UUID (never by numeric id, so passports can't be
     * enumerated by guessing sequential ids).
     */
    public function show(string $qrCode): View|Response
    {
        $vehicle = Vehicle::query()
            ->where('qr_code', $qrCode)
            ->with([
                'branch:id,name,phone',
                'jobCards' => fn ($q) => $q->whereNotNull('completed_at')
                    ->orderByDesc('completed_at')
                    ->with(['services', 'partsUsed.inventoryItem', 'media']),
            ])
            ->first();

        abort_if(! $vehicle, 404);

        return view('passport.show', [
            'vehicle' => $vehicle,
            'healthScores' => $vehicle->latestHealthScores(),
            'nextServiceDue' => $vehicle->nextServiceDue(),
            'jobCards' => $vehicle->jobCards,
        ]);
    }
}
