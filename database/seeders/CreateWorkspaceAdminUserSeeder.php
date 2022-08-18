<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateWorkspaceAdminUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Workspace Admin',
            'email' => 'workspaceadmin@example.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'Admin_Workspace']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
