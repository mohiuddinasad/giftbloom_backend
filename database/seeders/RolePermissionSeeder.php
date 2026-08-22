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

            // dashboard
            'dashboard-view',

            // users & roles
            'user-list', 'user-delete',
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'role-assign', 'permission-assign', 'permission-list',

            // categories
            'category-list', 'category-create', 'category-edit', 'category-delete',

            // products
            'product-list', 'product-create', 'product-edit', 'product-delete',

            // orders
            'order-list', 'order-view', 'order-edit', 'order-delete',

            // banners & videos
            'banner-list', 'banner-create', 'banner-edit', 'banner-delete',

            // settings
            'setting-view', 'setting-edit',
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
