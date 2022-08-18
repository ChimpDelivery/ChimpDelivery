<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'delete workspace',
            'view workspace',
            'update workspace',
            'create app',
            'view app',
            'update app',
            'delete app',
            'create bundle',
            'scan jobs',
            'build job',
            'abort job',
        ];

        // create all permissions
        foreach ($permissions as $permission)
        {
            Permission::create([
                'name' => $permission
            ]);
        }
    }
}
