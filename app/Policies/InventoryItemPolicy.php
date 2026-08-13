<?php

namespace App\Policies;

use App\Models\InventoryItem;
use App\Models\User;

class InventoryItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('inventory.view_any');
    }

    public function view(User $user, InventoryItem $item): bool
    {
        return $user->can('inventory.view_any');
    }

    public function create(User $user): bool
    {
        return $user->can('inventory.create');
    }

    public function update(User $user, InventoryItem $item): bool
    {
        return $user->can('inventory.update');
    }

    public function delete(User $user, InventoryItem $item): bool
    {
        return $user->can('inventory.delete');
    }

    public function restore(User $user, InventoryItem $item): bool
    {
        return $user->can('inventory.restore');
    }

    public function forceDelete(User $user, InventoryItem $item): bool
    {
        return $user->can('inventory.force_delete');
    }
}
