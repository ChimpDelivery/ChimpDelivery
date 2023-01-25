<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RestartHorizonQueue extends Command
{
    protected $signature = 'dashboard:restart-horizon';
    protected $description = 'Restarts horizon based queues...';

    public function handle() : int
    {
        $this->call('horizon:terminate');
        $this->call('horizon:purge');
        $this->call('queue:restart');

        return Command::SUCCESS;
    }

}
