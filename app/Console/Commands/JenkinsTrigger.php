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
            $url = implode('', [
                env('JENKINS_HOST', 'http://localhost:8080'),
                "/job/" . env('JENKINS_WS') . "/job/{$app->app_name}/job/master/build"
            ]);

            Http::withBasicAuth(env('JENKINS_USER'), env('JENKINS_TOKEN'))->post($url);
        }
    }
}
