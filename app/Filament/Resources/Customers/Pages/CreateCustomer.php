<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Password;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (! auth()->user()->canAccessAllBranches()) {
            $data['branch_id'] = auth()->user()->branch_id ?? 1;
        }
        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['branch_id'])) {
            $data['branch_id'] = auth()->user()->branch_id ?? 1;
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        $customer = $this->record;

        if ($customer->email) {
            Password::broker('customers')->sendResetLink(['email' => $customer->email]);
        }
    }
}
