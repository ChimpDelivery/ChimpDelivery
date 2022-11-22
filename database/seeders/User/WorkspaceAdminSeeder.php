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
            'name' => 'Talus CI',
            'email' => 'talusci@talusstudio.com',
        ])->syncRoles(['Admin_Workspace']);

        User::factory()->create([
            'workspace_id' => 3,
            'name' => 'Example Admin',
            'email' => 'exampleadmin@talusstudio.com',
        ])->syncRoles(['Admin_Workspace']);
    }
}
