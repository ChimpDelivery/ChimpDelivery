<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class WorkspaceUserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->createQuietly([
            'workspace_id' => 3,
            'name' => 'Example User',
            'email' => 'workspaceuser@' . config('seed.users.email_postfix'),
        ])->syncRoles(['User_Workspace']);
    }
}
