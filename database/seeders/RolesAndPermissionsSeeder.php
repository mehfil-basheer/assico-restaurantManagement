<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'manage tables']);
        Permission::create(['name' => 'manage reservations']);
        Permission::create(['name' => 'manage menus']);
        Permission::create(['name' => 'manage orders']);

        // Create roles and assign existing permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('manage tables');
        $adminRole->givePermissionTo('manage reservations');
        $adminRole->givePermissionTo('manage menus');
        $adminRole->givePermissionTo('manage orders');

        $staffRole = Role::create(['name' => 'staff']);
        $staffRole->givePermissionTo('manage reservations');
        $staffRole->givePermissionTo('manage orders');

        $customerRole = Role::create(['name' => 'customer']);
    }
}
