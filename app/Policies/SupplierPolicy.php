<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'mechanic', 'inventory_manager']);
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'mechanic', 'inventory_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'inventory_manager']);
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'inventory_manager']);
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function restore(User $user, Supplier $supplier): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return $user->hasRole('admin');
    }

}
