<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('site.services', [
            'services' => Service::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_active, 404);

        return view('site.service-detail', [
            'service' => $service,
            'allServices' => Service::active()->orderBy('sort_order')->get(),
        ]);
    }
}
