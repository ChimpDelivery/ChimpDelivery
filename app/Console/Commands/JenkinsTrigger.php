<?php

namespace App\Console\Commands;

use App\Models\AppInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class JenkinsTrigger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jenkins:trigger {appInfoID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger jenkins pipeline to build.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $app = AppInfo::where('id', $this->argument('appInfoID'))->first();
        if ($app)
        {
            $url = implode('', [
                env('JENKINS_HOST', 'http://localhost:8080'),
                "/job/{$app->app_name}/build?token=",
                env('JENKINS_TOKEN')
            ]);

            Http::withBasicAuth(env('JENKINS_USER'), env('JENKINS_PASS'))->get($url);
        }
    }
}
