<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WorkspaceInviteCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\WorkspaceInviteCode::factory(10)->create();
    }
}
