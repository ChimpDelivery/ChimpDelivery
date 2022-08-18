<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $userRole = Role::create([ 'name' => 'User' ]);
        $userRolePermissions = [
            'create workspace',
            'join workspace',
        ];
        $userRole->syncPermissions($userRolePermissions);

        $workspaceUserRole = Role::create([ 'name' => 'User_Workspace' ]);
        $workSpaceUserPermissions = [
            'create app',
            'update app',
            'create bundle',
            'scan jobs',
            'build job',
            'abort job',
        ];
        $workspaceUserRole->syncPermissions($workSpaceUserPermissions);

        $workspaceAdminRole = Role::create([ 'name' => 'Admin_Workspace' ]);
        $workspaceAdminPermissions = [
            'view workspace',
            'update workspace',
            'create app',
            'update app',
            'delete app',
            'create bundle',
            'scan jobs',
            'build job',
            'abort job',
        ];
        $workspaceAdminRole->syncPermissions($workspaceAdminPermissions);

        $superAdminRole = Role::create([ 'name' => 'Admin_Super' ]);
        $superAdminPerssions = [

        ];

        /*$role = Role::where('name', '=', 'Admin_Super')->firstOrFail();

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([ $role->id ]);*/
    }
}
