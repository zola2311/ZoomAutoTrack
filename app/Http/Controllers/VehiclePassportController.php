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
                'branch:id,name,phone,address,city',
                'customer:id,full_name,company_name,phone,type',
                'jobCards' => fn ($q) => $q->whereNotNull('completed_at')
                    ->orderByDesc('completed_at')
                    ->with(['services', 'partsUsed.inventoryItem', 'media', 'mechanic:id,name']),
                'inspections' => fn ($q) => $q->orderByDesc('inspected_at')
                    ->with(['answers.inspectionItem', 'inspector:id,name']),
            ])
            ->first();

        abort_if(! $vehicle, 404);

        // Odometer verification analysis (checks for rollbacks across chronological visits)
        $chronologicalJobs = $vehicle->jobCards->sortBy('completed_at')->values();
        $odometerIssues = [];
        $previousMileage = 0;
        foreach ($chronologicalJobs as $index => $job) {
            $m = (int) $job->mileage_at_checkin;
            if ($index > 0 && $m < $previousMileage) {
                $odometerIssues[] = [
                    'date' => $job->completed_at?->format('d M Y'),
                    'reported' => $m,
                    'previous' => $previousMileage,
                ];
            }
            if ($m > 0) {
                $previousMileage = $m;
            }
        }

        $odometerStatus = [
            'is_verified' => empty($odometerIssues),
            'issues_count' => count($odometerIssues),
            'issues' => $odometerIssues,
            'first_recorded_mileage' => $chronologicalJobs->first()?->mileage_at_checkin ?? $vehicle->current_mileage,
            'latest_mileage' => $vehicle->current_mileage,
            'total_services' => $vehicle->jobCards->count(),
        ];

        return view('passport.show', [
            'vehicle' => $vehicle,
            'healthScores' => $vehicle->latestHealthScores(),
            'nextServiceDue' => $vehicle->nextServiceDue(),
            'jobCards' => $vehicle->jobCards,
            'latestInspection' => $vehicle->latestInspection(),
            'odometerStatus' => $odometerStatus,
        ]);
    }

    /**
     * Look up a digital vehicle passport by plate number or chassis number.
     */
    public function lookup(\Illuminate\Http\Request $request): \Illuminate\Http\RedirectResponse
    {
        $query = trim($request->input('query', ''));
        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a plate number or chassis number.');
        }

        $vehicle = Vehicle::query()
            ->whereNotNull('qr_code')
            ->where(function ($q) use ($query) {
                $q->where('plate_number', 'LIKE', "%{$query}%")
                  ->orWhere('chassis_number', 'LIKE', "%{$query}%")
                  ->orWhere('qr_code', $query);
            })
            ->first();

        if (! $vehicle) {
            return redirect()->back()->with('error', 'No vehicle found matching "' . e($query) . '". Please check the plate number or contact the workshop.');
        }

        return redirect()->route('passport.show', $vehicle->qr_code);
    }

    /**
     * Formal printable Used Vehicle Inspection Certificate
     */
    public function certificate(string $qrCode): View|Response
    {
        $vehicle = Vehicle::query()
            ->where('qr_code', $qrCode)
            ->with([
                'branch',
                'customer',
                'inspections.answers.inspectionItem',
                'inspections.inspector',
            ])
            ->first();

        abort_if(! $vehicle, 404);

        return view('passport.certificate', [
            'vehicle' => $vehicle,
            'healthScores' => $vehicle->latestHealthScores(),
            'latestInspection' => $vehicle->latestInspection(),
            'nextServiceDue' => $vehicle->nextServiceDue(),
        ]);
    }
}
