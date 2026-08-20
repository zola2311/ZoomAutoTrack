<?php

namespace App\Filament\Resources\Vehicles\Pages;

use App\Filament\Resources\Vehicles\VehicleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Filament\Facades\Filament;

class CreateVehicle extends CreateRecord
{
    protected static string $resource = VehicleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['branch_id'] = Filament::auth()->user()->branch_id ?? 1;

        if (empty($data['qr_code'])) {
            $data['qr_code'] = (string) Str::uuid();
        }

        return $data;
    }
}
