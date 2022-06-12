<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class CreateAppstoreSession extends Command
{
    protected $signature = 'appstore:create-session';
    protected $description = 'creates FASTLANE_SESSION data';

    private $twoFactorAuthBypass = 'sh /var/www/html/RubyBackend/TwoFactorBot.sh';

    public function handle()
    {
        $process = Process::fromShellCommandline($this->twoFactorAuthBypass);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
