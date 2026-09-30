<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use UnitEnum;

/**
 * One-click setup for shared hosting with no terminal/SSH access.
 * Runs `php artisan migrate` and creates the new Services/Team/Gallery
 * permissions from the browser, since Tinker isn't reachable either.
 *
 * Safe to leave installed after use — running migrate again just says
 * "Nothing to migrate", and creating permissions again is a no-op
 * (firstOrCreate). Restrict or remove the nav item later if you prefer
 * not to have it visible, see canAccess() below.
 */
class SystemSetup extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'System Setup';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.pages.system-setup';

    public string $migrationOutput = '';

    public string $permissionOutput = '';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public function runMigrations(): void
    {
        Artisan::call('migrate', ['--force' => true]);
        $this->migrationOutput = Artisan::output();

        Notification::make()
            ->title('Migrations run')
            ->success()
            ->send();
    }

    public function createNewPermissions(): void
    {
        $resources = ['services', 'team_members', 'gallery_images'];
        $actions = ['view_any', 'create', 'update', 'delete', 'restore', 'force_delete'];

        $created = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate([
                    'name' => "{$resource}.{$action}",
                    'guard_name' => 'web',
                ]);

                if ($permission->wasRecentlyCreated) {
                    $created[] = $permission->name;
                }
            }
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $this->permissionOutput = $created
            ? "Created:\n" . implode("\n", $created)
            : 'Nothing to create — all 18 permissions already exist.';

        Notification::make()
            ->title(count($created) . ' permission(s) created')
            ->body('Go to Role Permissions Matrix to grant them to a role.')
            ->success()
            ->send();
    }
}
