<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;

use App\Models\User;

class WorkspaceAdminSeeder extends Seeder
{
    public function run()
    {
        User::factory()->createQuietly([
            'workspace_id' => 2,
            'name' => 'Example Admin',
            'email' => 'example_admin_1@' . config('seed.users.email_postfix'),
        ])->syncRoles(['Admin_Workspace']);

        User::factory()->createQuietly([
            'workspace_id' => 3,
            'name' => 'Example Admin',
            'email' => 'example_admin_2@' . config('seed.users.email_postfix'),
        ])->syncRoles(['Admin_Workspace']);
    }
}
