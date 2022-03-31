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
        $url = config('jenkins.host').
            "/job/".
            config('jenkins.ws').
            "/job/{$this->argument('projectName')}/job/master/{$this->argument('buildNumber')}/stop";

        echo 'Jenkins response code: ' . Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url)->status();
    }
}
