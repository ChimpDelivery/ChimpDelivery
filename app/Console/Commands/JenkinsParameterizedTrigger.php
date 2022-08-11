<?php

namespace App\Console\Commands;

use App\Models\AppInfo;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;

class JenkinsParameterizedTrigger extends Command
{
    protected $signature = 'jenkins:trigger {appID} {branch} {invokeParameters} {platform} {storeVersion} {hasCustomBundleVersion} {storeBundleVersion}';
    protected $description = 'Trigger jenkins pipeline to build.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() : void
    {
        $app = AppInfo::find($this->argument('appID'));

        $url = config('jenkins.host').
            "/job/".
            config('jenkins.ws').
            "/job/{$app->project_name}/job/{$this->argument('branch')}/buildWithParameters".
            "?INVOKE_PARAMETERS={$this->argument('invokeParameters')}".
            "&PLATFORM={$this->argument('platform')}".
            "&APP_ID={$app->id}".
            "&STORE_BUILD_VERSION={$this->argument('storeVersion')}".
            "&STORE_CUSTOM_BUNDLE_VERSION={$this->argument('hasCustomBundleVersion')}".
            "&STORE_BUNDLE_VERSION={$this->argument('storeBundleVersion')}";

        Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }
}
