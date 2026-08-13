<x-filament-panels::page>
    @php
        $roles = \Spatie\Permission\Models\Role::all();
        $permissionsGrouped = \Spatie\Permission\Models\Permission::all()->groupBy(function($item) {
            return str_contains($item->name, '.') ? explode('.', $item->name)[0] : 'general';
        });
    @endphp

    <form wire:submit.prevent="save" style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div style="background-color: white; border: 1px solid #e5e7eb; border-radius: 0.75rem; overflow-x: auto; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead>
                <tr style="border-bottom: 1px solid #e5e7eb; background-color: #f9fafb;">
                    <th style="padding: 0.875rem 1rem; font-weight: 600; color: #374151; min-width: 220px;">Permission Name</th>
                    @foreach($roles as $role)
                        <th wire:key="header-role-{{ $role->id }}" style="padding: 0.875rem 1rem; font-weight: 600; text-align: center; color: #374151; min-width: 140px;">
                            <div>{{ str($role->name)->replace('_', ' ')->title() }}</div>
                            <button
                                type="button"
                                wire:click="toggleRoleColumn({{ $role->id }})"
                                style="margin-top: 0.25rem; font-size: 0.7rem; color: #6366f1; text-decoration: underline; background: none; border: none; cursor: pointer; font-weight: 500;"
                            >
                                Toggle Column
                            </button>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($permissionsGrouped as $groupKey => $groupPermissions)
                    @php
                        $cleanGroupKey = (string) $groupKey;
                    @endphp
                    <tr wire:key="group-hdr-{{ $cleanGroupKey }}" style="background-color: #f3f4f6; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.5rem 1rem; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #4f46e5;">
                            <span>{{ str($cleanGroupKey)->replace('_', ' ')->title() }}</span>
                            <button
                                type="button"
                                wire:click="toggleGroupAllRoles('{{ $cleanGroupKey }}')"
                                style="margin-left: 0.75rem; font-size: 0.7rem; color: #4f46e5; text-decoration: underline; background: none; border: none; cursor: pointer; text-transform: none; font-weight: 600;"
                            >
                                (Toggle All Roles)
                            </button>
                        </td>
                        @foreach($roles as $role)
                            <td wire:key="grp-cell-{{ $cleanGroupKey }}-{{ $role->id }}" style="padding: 0.5rem 1rem; text-align: center;">
                                <button
                                    type="button"
                                    wire:click="toggleGroupRole('{{ $cleanGroupKey }}', {{ $role->id }})"
                                    style="font-size: 0.7rem; color: #4b5563; text-decoration: underline; background: none; border: none; cursor: pointer; font-weight: 500;"
                                >
                                    Toggle
                                </button>
                            </td>
                        @endforeach
                    </tr>
                    @foreach($groupPermissions as $permission)
                        <tr wire:key="perm-row-{{ $permission->id }}" style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 0.75rem 1rem; color: #4b5563; font-weight: 500;">
                                <code>{{ $permission->name }}</code>
                            </td>
                            @foreach($roles as $role)
                                <td wire:key="cell-{{ $role->id }}-{{ $permission->id }}" style="padding: 0.75rem 1rem; text-align: center;">
                                    <input
                                        type="checkbox"
                                        wire:model="matrix.{{ $role->id }}.{{ $permission->id }}"
                                        style="width: 1.125rem; height: 1.125rem; accent-color: #6366f1; cursor: pointer;"
                                    >
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: flex-end;">
            <x-filament::button type="submit" size="lg">
                Save Permission Matrix
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
