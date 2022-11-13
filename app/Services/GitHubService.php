<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use App\Models\GithubSetting;

class GitHubService
{
    private readonly GithubSetting $githubSetting;

    public function __construct()
    {
        $this->ResolveGithubSetting();
        $this->SetConnectionToken();
    }

    public function GetSettings() : GithubSetting
    {
        return $this->githubSetting;
    }

    public function ResolveGithubSetting() : void
    {
        $isWebUser = Auth::guard('web')->check();

        $this->githubSetting = $isWebUser
            ? Auth::user()->workspace->githubSetting
            : Auth::user()->githubSetting;
    }

    public function SetConnectionToken() : void
    {
        Config::set('github.connections.main.token', $this->githubSetting->personal_access_token ?? 'INVALID TOKEN');
    }
}
