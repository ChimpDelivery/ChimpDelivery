<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Http;

class JenkinsCreateOrganization extends Command
{
    protected $signature = 'jenkins:create-organization {wsName} {gitUserName} {gitAccessToken} {githubTopic} {repoOwner}';
    protected $description = 'Trigger Jenkins Seed job to create new workspace in Jenkins.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle() : void
    {
        $url = config('jenkins.host').
            "/job".
            "/Seed".
            "/buildWithParameters".
            "?WORKSPACE_NAME={$this->argument('wsName')}".
            "&GIT_CREDENTIALS={$this->argument('gitUserName')}/{$this->argument('gitAccessToken')}".
            "&GITHUB_TOPIC={$this->argument('githubTopic')}".
            "&REPO_OWNER={$this->argument('repoOwner')}";

        echo Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }
}
