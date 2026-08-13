<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'service_advisor', 'receptionist', 'cashier']);
    }

    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'service_advisor', 'receptionist', 'cashier']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'service_advisor', 'receptionist']);
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'service_advisor', 'receptionist']);
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return $user->hasRole('admin');
    }
}
