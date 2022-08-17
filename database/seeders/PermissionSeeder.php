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

        ////////////////
        $user = Role::create([ 'name' => 'User' ]);

        $userPermissions = [
            'view app',
            'create app',
            'update app',
            'scan jobs',
            'build job',
            'abort job',
        ];

        foreach ($userPermissions as $permission)   {
            $user->givePermissionTo($permission);
        }
        //////////////
        
        //////////////
        $workspaceAdmin = Role::create([ 'name' => 'Workspace Admin' ]);

        $workspaceAdminPermissions = [
            'view workspace',
            'update workspace',
            'view app',
            'create app',
            'update app',
            'delete app',
            'scan jobs',
            'build job',
            'abort job',
        ];

        foreach ($workspaceAdminPermissions as $permission) {
            $workspaceAdmin->givePermissionTo($permission);
        }
        ///////////////

        // gets all permissions via Gate::before rule; see AuthServiceProvider
        Role::create([ 'name' => 'Super Admin' ]);
    }
}
