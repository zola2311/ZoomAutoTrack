<?php

namespace App\Filament\Resources\JobCards\Pages;

use App\Filament\Resources\JobCards\JobCardResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobCard extends CreateRecord
{
    protected static string $resource = JobCardResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['branch_id'])) {
            $data['branch_id'] = auth()->user()->branch_id ?? 1;
        }

        return $data;
    }
}
