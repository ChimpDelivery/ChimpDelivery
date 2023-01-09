<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use GrahamCampbell\GitHub\Facades\GitHub;

use App\Models\GithubSetting;

class GitHubService
{
    private readonly GithubSetting $githubSetting;

    public function __construct()
    {
        $this->githubSetting = Auth::user()->workspace->githubSetting;

        Config::set('github.connections.main.token', $this->githubSetting->personal_access_token ?? 'INVALID TOKEN');
    }

    public function GetSettings() : GithubSetting
    {
        return $this->githubSetting;
    }

    public function GetUserOrganizations()
    {
        return GitHub::api('user')->orgs();
    }
}
