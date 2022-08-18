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
            'name' => 'Default',
            'appstore_private_key' => null,
            'appstore_issuer_id' => null,
            'appstore_kid' => null,
            'apple_usermail' => null,
            'apple_app_pass' => null,
            'github_org_name' => null,
            'github_access_token' => null,
            'github_template' => null,
            'github_topic' => null,
        ]);

        // seed demo Workspaces
        Workspace::factory(2)->create();
    }
}
