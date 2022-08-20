<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

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
            Auth::user()->workspace->githubSetting->organization_name.
            "/job/{$this->argument('projectName')}/job/master/{$this->argument('buildNumber')}/stop";

        Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }
}
