<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $operatorRole = Role::firstOrCreate(['name' => 'Operator']);

        $admin = User::updateOrCreate(
            ['email' => 'moleng'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('hangus'),
            ],
        );

        $admin->assignRole($adminRole);
    }
}
