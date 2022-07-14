<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Symfony\Component\Process\Process;

class CreateAppstoreApp extends Command
{
    protected $signature = 'appstore:create-app {bundleId} {appName}';
    protected $description = 'Creates App on App Store Connect with provided Bundle Identifier.';

    private $scriptPathRoot = '/var/www/html/AppstoreAutomation';
    private $scriptName = 'CreateAppstoreApplication.rb';

    public function handle()
    {
        $companyName = config('appstore.company_name');

        $createAppCommand = "cd {$this->scriptPathRoot} && ruby {$this->scriptPathRoot}/{$this->scriptName} {$this->argument('bundleId')} {$this->argument('appName')} {$companyName}";

        $process = Process::fromShellCommandline($createAppCommand, null, [
            'FASTLANE_USER' => config('appstore.user_email'),
            'FASTLANE_PASSWORD' => config('appstore.user_pass'),
            'LC_ALL' => 'en_US.UTF-8',
            'LANG' => 'en_US.UTF-8',
            'LANGAUGE' => 'en_US.UTF-8'
        ]);
        $process->setTimeout(120);
        $process->setIdleTimeout(30);
        $process->run();

        while ($process->isRunning())
        {

        }

        $response = collect();
        $response->put('status', $process->getExitCode());
        $response->put('exit_message', $process->getExitCodeText());
        $response->put('output', $process->getOutput());

        $this->info($response);
    }
}
