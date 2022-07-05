<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Symfony\Component\Process\Process;

class CreateAppstoreApp extends Command
{
    protected $signature = 'appstore:create-app {bundleId} {appName}';
    protected $description = 'Creates App on App Store Connect with provided Bundle Identifier.';

    private $scriptPath = '/var/www/html/AppstoreAutomation/CreateAppBridge.sh';

    public function handle()
    {
        $appStoreUser = config('appstore.user_email');
        $appStorePass = config('appstore.user_pass');
        $companyName = config('appstore.company_name');

        $createAppCommand = "sh {$this->scriptPath} {$appStoreUser} {$appStorePass} {$this->argument('bundleId')} {$this->argument('appName')} {$companyName}";

        $process = Process::fromShellCommandline($createAppCommand);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if ($process->isTerminated())
        {
            echo "Exit Code:" . $process->getExitCode() . "\n\r";
            echo "Exit Text:" . $process->getExitCodeText() . "\n\r";
            echo "Error Output:" . $process->getErrorOutput() . "\n\r";
        }
    }
}
