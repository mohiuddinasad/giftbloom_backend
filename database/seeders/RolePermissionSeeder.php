<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

            'product-create', 'product-edit', 'product-delete',
            'role-create', 'role-edit', 'role-delete',
            'role-assign', 'permission-assign',
            'dashboard-view', 'user-list', 'role-list', 'permission-list',
            'order-list', 'order-edit', 'order-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'Customer']);

        $user = User::first();
        if ($user) {
            $user->assignRole('Super Admin');
        }
    }
}
