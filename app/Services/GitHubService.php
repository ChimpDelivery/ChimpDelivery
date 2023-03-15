<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use GrahamCampbell\GitHub\Facades\GitHub;

use App\Models\GithubSetting;

class GitHubService
{
    public readonly GithubSetting $setting;

    public function __construct()
    {
        $this->setting = Auth::user()->workspace->githubSetting;

        Config::set(
            'github.connections.main.token',
            $this->setting->personal_access_token ?? 'INVALID_TOKEN'
        );
    }

    public function GetUserOrganizations()
    {
        return GitHub::api('user')->orgs();
    }
}
