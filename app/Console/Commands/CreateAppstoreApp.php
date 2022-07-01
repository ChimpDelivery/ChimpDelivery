<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class CreateAppstoreApp extends Command
{
    protected $signature = 'appstore:create-app {bundleId} {bundleName} {appName}';
    protected $description = 'creates bundle id and app on appstore connect';

    private $scriptPath = '/var/www/html/RubyBackend/TwoFactorBot.sh';

    public function handle()
    {
        $appStoreUser = config('appstore.user_email');
        $appStorePass = config('appstore.user_pass');
        $companyName = config('appstore.company_name');

        $createAppCommand = "sh {$this->scriptPath} {$appStoreUser} {$appStorePass} {$this->argument('bundleId')} {$this->argument('bundleName')} {$this->argument('appName')} {$companyName}";

        $process = Process::fromShellCommandline($createAppCommand);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
