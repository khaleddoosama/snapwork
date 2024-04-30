<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;



class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $ceo = Role::create([
            'name' => 'CEO',
            'guard_name' => 'web',
        ]);

        $account = Role::create([
            'name' => 'Account',
            'guard_name' => 'web',
        ]);

        $permissions = Permission::pluck('name');

        if ($permissions) {
            $superAdmin->givePermissionTo($permissions);
        }
    }
}
