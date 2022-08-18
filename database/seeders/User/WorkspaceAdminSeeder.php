<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class WorkspaceAdminSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'workspace_id' => 2,
            'name' => 'Workspace Admin',
            'email' => 'workspaceadmin@example.com',
        ])->syncRoles([ 'Admin_Workspace' ]);
    }
}
