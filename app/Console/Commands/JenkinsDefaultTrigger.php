<?php

namespace App\Console\Commands;

use App\Models\AppInfo;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class JenkinsDefaultTrigger extends Command
{
    protected $signature = 'jenkins:default-trigger {appInfoID}';
    protected $description = 'Trigger jenkins pipeline to build.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() : void
    {
        $app = AppInfo::find($this->argument('appInfoID'));

        $url = config('jenkins.host').
            "/job/".
            Auth::user()->workspace->githubSetting->organization_name.
            "/job/{$app->project_name}/job/master/build?delay=0sec";

        Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }
}
