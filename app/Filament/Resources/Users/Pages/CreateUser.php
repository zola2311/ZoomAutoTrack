<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        // Assign the selected role after user is created
        $role = $this->data['role'] ?? null;
        if ($role) {
            $this->record->assignRole($role);
        }
    }
}
