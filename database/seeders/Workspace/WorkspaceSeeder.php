<?php

namespace Database\Seeders\Workspace;

use Illuminate\Database\Seeder;

use App\Models\Workspace;

class WorkspaceSeeder extends Seeder
{
    public function run()
    {
        // seed default Workspace for new Users
        Workspace::factory(1)->create([ 'name' => 'Default' ]);

        // seed demo Workspaces
        Workspace::factory(2)->create();

        Workspace::find(1)->createToken('api-key');
        Workspace::find(2)->createToken('api-key');
        Workspace::find(3)->createToken('api-key');
    }
}
