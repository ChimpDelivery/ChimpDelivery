<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'workspace_id' => 1,
            'email' => 'user1@example.com',
            'name' => 'User',
            'password' => bcrypt('123456')
        ]);

        $role = Role::where('name', '=', 'User')->firstOrFail();

        $permissions = [
            'create workspace',
            'join workspace'
        ];

        $role->syncPermissions($permissions);

        $user->assignRole([ $role->id ]);
    }
}
