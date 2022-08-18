<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use Spatie\Permission\Models\Role;

class WorkspaceUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Workspace User',
            'email' => 'workspaceuser@example.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::where('name', '=', 'User')->firstOrFail();

        $permissions = [
            'create app',
            'update app',
            'create bundle',
            'scan jobs',
            'build job',
            'abort job'
        ];

        $role->syncPermissions($permissions);

        $user->assignRole([ $role->id ]);
    }
}
