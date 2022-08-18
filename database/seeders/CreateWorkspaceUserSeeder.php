<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateWorkspaceUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Workspace User',
            'email' => 'workspaceuser@example.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'User']);

        $permissions = [
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
