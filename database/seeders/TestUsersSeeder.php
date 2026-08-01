<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $branchId = Branch::first()?->id ?? Branch::create([
            'name'      => 'AutoTrack Bole',
            'phone'     => '+251 11 123 4567',
            'address'   => 'Bole Road, Addis Ababa',
            'is_active' => true,
        ])->id;

        $testUsers = [
            [
                'name'     => 'Test Manager',
                'email'    => 'manager@autotrack.et',
                'role'     => 'manager',
                'phone'    => '+251 91 111 0001',
            ],
            [
                'name'     => 'Test Service Advisor',
                'email'    => 'advisor@autotrack.et',
                'role'     => 'service_advisor',
                'phone'    => '+251 91 111 0002',
            ],
            [
                'name'     => 'Test Mechanic One',
                'email'    => 'mechanic1@autotrack.et',
                'role'     => 'mechanic',
                'phone'    => '+251 91 111 0003',
            ],
            [
                'name'     => 'Test Mechanic Two',
                'email'    => 'mechanic2@autotrack.et',
                'role'     => 'mechanic',
                'phone'    => '+251 91 111 0004',
            ],
            [
                'name'     => 'Test Receptionist',
                'email'    => 'reception@autotrack.et',
                'role'     => 'receptionist',
                'phone'    => '+251 91 111 0005',
            ],
            [
                'name'     => 'Test Cashier',
                'email'    => 'cashier@autotrack.et',
                'role'     => 'cashier',
                'phone'    => '+251 91 111 0006',
            ],
            [
                'name'     => 'Test Inventory Manager',
                'email'    => 'inventory@autotrack.et',
                'role'     => 'inventory_manager',
                'phone'    => '+251 91 111 0007',
            ],
        ];

        foreach ($testUsers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'branch_id' => $branchId,
                    'name'      => $data['name'],
                    'password'  => Hash::make('password'),
                    'phone'     => $data['phone'],
                    'role'      => $data['role'],
                    'is_active' => true,
                ]
            );

            $user->syncRoles([$data['role']]);
        }

        $this->command->info('Test users created. All passwords: password');
        $this->command->table(
            ['Name', 'Email', 'Role'],
            collect($testUsers)->map(fn ($u) => [$u['name'], $u['email'], $u['role']])->toArray()
        );
    }
}
