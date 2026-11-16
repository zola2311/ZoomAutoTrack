<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;

class QuotationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'manager', 'service_advisor', 'receptionist']);
    }

    public function view(User $user, Quotation $quotation): bool
    {
        return $user->hasRole(['admin', 'manager', 'service_advisor', 'receptionist']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'manager', 'service_advisor']);
    }

    public function update(User $user, Quotation $quotation): bool
    {
        return $user->hasRole(['admin', 'manager', 'service_advisor']);
    }

    public function delete(User $user, Quotation $quotation): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    public function restore(User $user, Quotation $quotation): bool
    {
        return $user->hasRole(['admin']);
    }
}
