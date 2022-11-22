<?php

namespace App\Actions\Api\Jenkins\Post\DSL;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

use App\Actions\Api\Jenkins\Interfaces\BaseJenkinsAction;

class CreateOrganization extends BaseJenkinsAction
{
    public function handle(Request $request)
    {
        $url = config('jenkins.host').
            "/job".
            "/Seed".
            "/buildWithParameters?".
            "&GIT_USERNAME={$request->git_username}".
            "&GIT_ACCESS_TOKEN={$request->git_acess_token}".
            "&GITHUB_TOPIC={$request->github_topic}".
            "&REPO_OWNER={$request->git_organization}";

        return Http::withBasicAuth(config('jenkins.user'), config('jenkins.token'))->post($url);
    }

    public function authorize(Request $request) : bool
    {
        return Auth::user()->hasRole('Admin_Super');
    }
}
