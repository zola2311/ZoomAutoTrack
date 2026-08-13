<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use UnitEnum;

class RolePermissions extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Role Permissions Matrix';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.role-permissions';

    // $matrix structure: matrix[role_id][permission_id] = boolean
    public array $matrix = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('users.view_any') ?? false;
    }

    public function mount(): void
    {
        $this->loadMatrix();
    }

    public function loadMatrix(): void
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        $this->matrix = [];

        foreach ($roles as $role) {
            $rolePermissionIds = $role->permissions->pluck('id')->toArray();
            foreach ($permissions as $permission) {
                $this->matrix[$role->id][$permission->id] = in_array($permission->id, $rolePermissionIds);
            }
        }
    }

    // Toggle entire Role Column
    public function toggleRoleColumn(int $roleId): void
    {
        if (! isset($this->matrix[$roleId])) {
            return;
        }

        $allChecked = ! in_array(false, array_values($this->matrix[$roleId]), true);

        foreach ($this->matrix[$roleId] as $permissionId => $status) {
            $this->matrix[$roleId][$permissionId] = ! $allChecked;
        }
    }

    // Toggle Group for a single Role
    public function toggleGroupRole(string $groupKey, int $roleId): void
    {
        $groupPermissionIds = Permission::all()
            ->filter(function ($p) use ($groupKey) {
                $prefix = str_contains($p->name, '.') ? explode('.', $p->name)[0] : 'general';
                return strtolower($prefix) === strtolower($groupKey);
            })
            ->pluck('id');

        $currentStatuses = [];
        foreach ($groupPermissionIds as $permId) {
            $currentStatuses[] = $this->matrix[$roleId][$permId] ?? false;
        }

        $allChecked = count($currentStatuses) > 0 && ! in_array(false, $currentStatuses, true);

        foreach ($groupPermissionIds as $permId) {
            $this->matrix[$roleId][$permId] = ! $allChecked;
        }
    }

    // Toggle Group for ALL Roles
    public function toggleGroupAllRoles(string $groupKey): void
    {
        $roles = Role::all();
        $groupPermissionIds = Permission::all()
            ->filter(function ($p) use ($groupKey) {
                $prefix = str_contains($p->name, '.') ? explode('.', $p->name)[0] : 'general';
                return strtolower($prefix) === strtolower($groupKey);
            })
            ->pluck('id');

        $allChecked = true;
        foreach ($roles as $role) {
            foreach ($groupPermissionIds as $permId) {
                if (! ($this->matrix[$role->id][$permId] ?? false)) {
                    $allChecked = false;
                    break 2;
                }
            }
        }

        foreach ($roles as $role) {
            foreach ($groupPermissionIds as $permId) {
                $this->matrix[$role->id][$permId] = ! $allChecked;
            }
        }
    }

    public function save(): void
    {
        $roles = Role::all()->keyBy('id');
        $allPermissions = Permission::all()->keyBy('id');
        $adminRole = $roles->firstWhere('name', 'admin');
        if ($adminRole && empty(array_filter($this->matrix[$adminRole->id] ?? []))) {
            Notification::make()
                ->title('Cannot save')
                ->body('The Admin role must keep at least one permission to prevent lockout.')
                ->danger()
                ->send();
            return;
        }

        foreach ($this->matrix as $roleId => $permissionMap) {
            if (! isset($roles[$roleId])) {
                continue;
            }

            $activePermissionIds = array_keys(array_filter($permissionMap));

            // Convert IDs back to permission names or pass directly to syncPermissions
            $activePermissionNames = $allPermissions->whereIn('id', $activePermissionIds)->pluck('name')->toArray();

            $roles[$roleId]->syncPermissions($activePermissionNames);
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        Notification::make()
            ->title('Permissions updated successfully!')
            ->success()
            ->send();
    }
}
