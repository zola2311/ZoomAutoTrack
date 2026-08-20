<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class VehicleQrStickerController extends Controller
{
    /**
     * Printable QR sticker for a vehicle — plate number + QR code
     * linking to its public Digital Passport, sized for a small
     * adhesive label. Staff-only (auth + policy), unlike the public
     * passport route.
     */
    public function show(Vehicle $vehicle): View
    {
        Gate::authorize('view', $vehicle);

        abort_if(! $vehicle->qr_code, 404, 'This vehicle has no QR code yet.');

        return view('vehicles.qr-sticker', [
            'vehicle' => $vehicle,
        ]);
    }
}
