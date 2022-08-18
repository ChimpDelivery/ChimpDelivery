<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WorkspaceSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Workspace::factory(3)->create();
    }
}
