<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use Spatie\Permission\Models\Role;

class WorkspaceAdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'workspace_id' => 2,
            'name' => 'Workspace Admin',
            'email' => 'workspaceadmin@example.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::where('name', '=', 'Admin_Workspace')->firstOrFail();

        $permissions = [
            'view workspace',
            'update workspace',
            'create app',
            'update app',
            'delete app',
            'create bundle',
            'scan jobs',
            'build job',
            'abort job'
        ];

        $role->syncPermissions($permissions);

        $user->assignRole([ $role->id ]);
    }
}
