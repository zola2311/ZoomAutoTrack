<?php

namespace App\Policies;

use App\Models\InspectionItem;
use App\Models\User;

class InspectionItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function view(User $user, InspectionItem $item): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function update(User $user, InspectionItem $item): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function delete(User $user, InspectionItem $item): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, InspectionItem $item): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, InspectionItem $item): bool
    {
        return $user->hasRole('admin');
    }
}
