<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Step 1 — Run pending migrations</x-slot>
            <x-slot name="description">Creates the services, team_members, and gallery_images tables.</x-slot>

            <x-filament::button wire:click="runMigrations" wire:loading.attr="disabled">
                Run Migrations
            </x-filament::button>

            @if ($migrationOutput)
                <pre class="mt-4 p-4 rounded-lg bg-gray-950 text-gray-100 text-xs overflow-x-auto">{{ $migrationOutput }}</pre>
            @endif
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Step 2 — Create the new permissions</x-slot>
            <x-slot name="description">
                Adds 18 permissions (view_any, create, update, delete, restore, force_delete
                for services, team_members, and gallery_images) so they appear in the Role
                Permissions Matrix. Safe to click more than once.
            </x-slot>

            <x-filament::button wire:click="createNewPermissions" wire:loading.attr="disabled" color="success">
                Create Permissions
            </x-filament::button>

            @if ($permissionOutput)
                <pre class="mt-4 p-4 rounded-lg bg-gray-950 text-gray-100 text-xs overflow-x-auto">{{ $permissionOutput }}</pre>
            @endif
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Step 3 — Grant the permissions</x-slot>
            <p class="text-sm text-gray-500">
                Once created, go to <strong>Role Permissions Matrix</strong> and tick the
                Services / Team Members / Gallery Images rows for whichever roles should
                manage them (usually Admin and Manager).
            </p>
        </x-filament::section>
    </div>
</x-filament-panels::page>
