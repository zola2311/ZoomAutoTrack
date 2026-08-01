<?php

namespace App\Filament\Resources\JobCards\Pages;

use App\Filament\Resources\JobCards\JobCardResource;
use App\Models\Media;
use App\Models\JobCard;
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

    protected function afterCreate(): void
    {
        $formData = $this->form->getRawState();

        // Sync photo collections
        foreach (['checkin_photos', 'damage_photos', 'before_photos', 'after_photos', 'checkout_photos'] as $collection) {
            if (isset($formData[$collection])) {
                $this->record->syncMediaCollection($formData[$collection], $collection);
            }
        }

        // Sync voice notes
        // Sync voice notes (repeater-based, multiple recordings per collection)
        foreach (['customer_complaint_voices', 'mechanic_notes_voices'] as $collection) {
            if (isset($formData[$collection])) {
                $paths = collect($formData[$collection])
                    ->pluck('path')
                    ->filter()
                    ->values()
                    ->toArray();

                $this->record->syncMediaCollection($paths, $collection);
            }
        }
    }
}
