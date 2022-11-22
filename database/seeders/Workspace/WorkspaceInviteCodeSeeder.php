<?php

namespace Database\Seeders\Workspace;

use Illuminate\Database\Seeder;

use App\Models\WorkspaceInviteCode;

class WorkspaceInviteCodeSeeder extends Seeder
{
    public function run()
    {
        WorkspaceInviteCode::factory(10)->create();
    }
}
