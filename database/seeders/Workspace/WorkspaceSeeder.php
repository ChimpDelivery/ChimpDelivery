<?php

namespace Database\Seeders\Workspace;

use Illuminate\Database\Seeder;

use App\Models\Workspace;

class WorkspaceSeeder extends Seeder
{
    public function run()
    {
        // seed default Workspace for new Users
        Workspace::factory(1)->createQuietly([
            'id' => 1,
            'name' => config('workspaces.default_org_name')
        ]);

        // seed internal Workspace
        Workspace::factory(1)->createQuietly([
            'id' => 2,
            'name' => 'TalusStudio'
        ]);

        // seed demo Workspaces
        Workspace::factory(3)->createQuietly();
    }
}
