<?php

namespace App\Policies;

use App\Models\InventoryItem;
use App\Models\User;

class InventoryItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'service_advisor', 'mechanic', 'inventory_manager']);
    }

    public function view(User $user, InventoryItem $item): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'service_advisor', 'mechanic', 'inventory_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'inventory_manager']);
    }

    public function update(User $user, InventoryItem $item): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'inventory_manager']);
    }

    public function delete(User $user, InventoryItem $item): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function restore(User $user, InventoryItem $item): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function forceDelete(User $user, InventoryItem $item): bool
    {
        return $user->hasRole('admin');
    }
}
