<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Branch
        $branchId = DB::table('branches')->insertGetId([
            'name'       => 'AutoTrack Bole',
            'phone'      => '+251 11 123 4567',
            'address'    => 'Bole Road, Addis Ababa',
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Admin user
        User::create([
            'branch_id'  => $branchId,
            'name'       => 'AutoTrack Admin',
            'email'      => 'admin@autotrack.et',
            'password'   => Hash::make('password'),
            'phone'      => '+251 91 000 0000',
            'role'       => 'admin',
            'is_active'  => true,
        ]);
    }
}
