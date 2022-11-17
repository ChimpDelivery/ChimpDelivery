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

        $permissions = [
            'create workspace',
            'join workspace',
            'delete workspace',
            'view workspace',
            'update workspace',

            'create app',
            'view apps',
            'update app',
            'delete app',

            'create bundle',

            'scan jobs',
            'build job',
            'abort job',
            'view job log',
        ];

        foreach ($permissions as $permission) {
            Permission::create([ 'name' => $permission ]);
        }
    }
}
