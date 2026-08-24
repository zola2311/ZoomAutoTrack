<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('appointments.view_any');
    }

    public function view(User $user, Appointment $appointment): bool
    {
        return $user->can('appointments.view_any');
    }

    public function create(User $user): bool
    {
        return $user->can('appointments.create');
    }

    public function update(User $user, Appointment $appointment): bool
    {
        return $user->can('appointments.update');
    }

    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->can('appointments.delete');
    }

    public function restore(User $user, Appointment $appointment): bool
    {
        return $user->can('appointments.restore');
    }
}
