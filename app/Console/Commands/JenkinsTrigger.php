<?php

namespace App\Console\Commands;

use App\Models\AppInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class JenkinsTrigger extends Command
{
    protected $signature = 'jenkins:trigger {appInfoID}';
    protected $description = 'Trigger jenkins pipeline to build.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() : void
    {
        $app = AppInfo::where('id', $this->argument('appInfoID'))->first();
        if ($app)
        {
            $appName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $app->app_name);

            $url = config('jenkins.host').
                "/job/".
                config('jenkins.ws').
                "/job/{$appName}/job/master/build";

            echo 'Jenkins response code: ' . Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url)->status();
        }
    }
}
