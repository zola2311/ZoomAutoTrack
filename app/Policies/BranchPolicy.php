<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Branch $branch): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Branch $branch): bool
    {
        return $user->hasRole('admin');
    }
}
