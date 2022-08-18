<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WorkspaceInviteCodeSeeder extends Seeder
{
    public function run()
    {
        \App\Models\WorkspaceInviteCode::factory(10)->create();
    }
}
