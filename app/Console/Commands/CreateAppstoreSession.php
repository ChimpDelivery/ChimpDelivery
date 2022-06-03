<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class CreateAppstoreSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appstore:create-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'creates FASTLANE_SESSION data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $twoFactorAuth = '/var/www/html/RubyBackend/TwoFactorBot.sh';

        $process = Process::fromShellCommandline("sh $twoFactorAuth");
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
