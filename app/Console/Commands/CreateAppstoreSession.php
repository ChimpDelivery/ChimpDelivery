<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class CreateAppstoreSession extends Command
{
    protected $signature = 'appstore:create-session';
    protected $description = 'creates FASTLANE_SESSION data';

    private $appStoreUser = '';
    private $appStorePass = '';
    private $bundleId = 'com.Talus.SlingBelt';
    private $bundleName = 'SlingBelt';
    private $appName = 'SlingBelt';
    private $companyName = '';

    private $twoFactorAuthBypass;

    public function __construct()
    {
        parent::__construct();

        $this->appStoreUser = config('appstore.user_email');
        $this->appStorePass = config('appstore.user_pass');
        $this->companyName = config('appstore.company_name');

        $this->twoFactorAuthBypass = "sh /var/www/html/RubyBackend/TwoFactorBot.sh {$this->appStoreUser} {$this->appStorePass} {$this->bundleId} {$this->bundleName} {$this->appName} {$this->companyName}";
    }

    public function handle()
    {
        $process = Process::fromShellCommandline($this->twoFactorAuthBypass);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
    }
}
