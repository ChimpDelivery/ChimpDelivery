<?php

namespace App\Console\Commands;

use App\Models\AppInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class JenkinsJobStopper extends Command
{
    protected $signature = 'jenkins:stopper {projectName} {buildNumber}';
    protected $description = 'Trigger jenkins pipeline to stop.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() : void
    {
        $app = AppInfo::where('app_name', $this->argument('projectName'))->first();
        if ($app)
        {
            $url = implode('', [
                env('JENKINS_HOST', 'http://localhost:8080'),
                "/job/" . env('JENKINS_WS') . "/job/{$app->app_name}/job/master/{$this->argument('buildNumber')}/stop"
            ]);

            Http::withBasicAuth(env('JENKINS_USER'), env('JENKINS_TOKEN'))->post($url);        
        }
    }
}
