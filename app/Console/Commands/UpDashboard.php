<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpDashboard extends Command
{
    protected $signature = 'dashboard:up';
    protected $description = 'Refresh caches and disable maintenance mode...';

    public function handle() : int
    {
        $this->call('optimize:clear');
        $this->call('optimize');
        $this->call('view:cache');
        $this->call('event:cache');
        $this->call('up');

        return Command::SUCCESS;
    }

}
