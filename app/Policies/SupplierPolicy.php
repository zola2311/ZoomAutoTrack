<?php

namespace App\Policies;

use App\Models\Supplier;
use App\Models\User;

class SupplierPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('suppliers.view_any');
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.view_any');
    }

    public function create(User $user): bool
    {
        return $user->can('suppliers.create');
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.update');
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.delete');
    }

    public function restore(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.restore');
    }

    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return $user->can('suppliers.force_delete');
    }
}
