<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class WorkspaceUserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'workspace_id' => 2,
            'name' => 'Workspace User',
            'email' => 'workspaceuser@example.com',
        ])->syncRoles([ 'User_Workspace' ]);
    }
}
