<?php

namespace Database\Seeders\Workspace;

use Illuminate\Database\Seeder;

use App\Models\Workspace;

class WorkspaceSeeder extends Seeder
{
    public function run()
    {
        // seed default Workspace for new Users
        Workspace::factory(1)->create([
            'name' => 'New-Registered-Users-WS',
            'api_key' => null,
        ]);

        // seed demo Workspaces
        Workspace::factory(2)->create();
    }
}
