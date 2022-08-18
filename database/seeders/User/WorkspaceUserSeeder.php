<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class WorkspaceUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'workspace_id' => 2,
            'name' => 'Workspace User',
            'email' => 'workspaceuser@example.com',
            'password' => bcrypt('123456')
        ]);

        $user->syncRoles([ 'User_Workspace' ]);
    }
}
