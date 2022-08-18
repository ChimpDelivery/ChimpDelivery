<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateWorkspaceAdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Workspace Admin',
            'email' => 'workspaceadmin@example.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'Admin_Workspace']);

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

        $user->assignRole([$role->id]);
    }
}
