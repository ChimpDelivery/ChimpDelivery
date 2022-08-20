<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class JenkinsScanRepoTrigger extends Command
{
    protected $signature = 'jenkins:scan-repo';
    protected $description = 'Trigger jenkins scanning.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() : void
    {
        $url = config('jenkins.host').
            "/job/".
            Auth::user()->workspace->githubSetting->organization_name.
            "/build?delay=0";

        Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }
}
