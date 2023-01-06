<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (config('permission-setup.permissions') as $permission)
        {
            Permission::create([ 'name' => $permission ]);
        }
    }
}
