<?php

namespace App\Actions\Api\Github;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use App\Models\GithubSetting;

abstract class BaseGithubAction
{
    public GithubSetting $githubSetting;

    public function ResolveGithubSetting(Request $request) : void
    {
        $this->githubSetting = ($request->expectsJson())
            ? Auth::user()->githubSetting
            : Auth::user()->workspace->githubSetting;
    }

    public function SetConnectionToken() : void
    {
        Config::set('github.connections.main.token', $this->githubSetting->personal_access_token ?? 'INVALID TOKEN');
    }
}
