<?php

namespace App\Filament\Resources\JobCards\Pages;

use App\Filament\Resources\JobCards\JobCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobCards extends ListRecords
{
    protected static string $resource = JobCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
