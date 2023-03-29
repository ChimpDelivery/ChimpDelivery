<?php

namespace Database\Seeders\Workspace;

use Illuminate\Database\Seeder;
use Symfony\Component\Console\Output\ConsoleOutput;

use App\Models\WorkspaceInviteCode;

class WorkspaceInviteCodeSeeder extends Seeder
{
    public function run()
    {
        $output = new ConsoleOutput();

        if (!app()->isLocal())
        {
            $output->writeln('Workspace Invite Code Seeder is not gonna run! Only local env allowed!');
            return;
        }

        WorkspaceInviteCode::factory(10)->create();
    }
}
