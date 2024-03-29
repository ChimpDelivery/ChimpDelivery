<?php

namespace App\Http\Requests\AppInfo;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Github\GetRepositoryRequest;

class StoreAppInfoRequest extends GetRepositoryRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'workspace_id' => [
                Rule::exists('workspaces', 'id')
                    ->whereNull('deleted_at'),
            ],

            'app_icon' => 'image|mimes:png|max:5120',

            'app_name' => [
                'required',
                'max:255',
                Rule::unique('app_infos')
                    ->ignore($this->id)
                    ->where('workspace_id', Auth::user()->workspace->id)
                    ->whereNull('deleted_at'),
            ],

            'project_name' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique('app_infos')
                    ->ignore($this->id)
                    ->where('workspace_id', Auth::user()->workspace->id)
                    ->whereNull('deleted_at'),
            ],

            'app_bundle' => [
                'required',
                'regex:/^[a-z][a-z0-9_]*(\.[a-z0-9_]+)+[0-9a-z_]$/i',
                'max:255',
                Rule::unique('app_infos')
                    ->ignore($this->id)
                    ->where('workspace_id', Auth::user()->workspace->id)
                    ->whereNull('deleted_at'),
            ],

            'appstore_id' => [
                'required',
                Rule::unique('app_infos')
                    ->ignore($this->id)
                    ->where('workspace_id', Auth::user()->workspace->id)
                    ->whereNull('deleted_at'),
            ],

            'fb_app_id' => [
                'nullable',
                'alpha_num',
                'max:255',
            ],

            'fb_client_token' => [
                'nullable',
                'alpha_num',
                'max:255',
            ],

            'ga_id' => [
                'nullable',
                'alpha_num',
                'max:255',
            ],

            'ga_secret' => [
                'nullable',
                'alpha_num',
                'max:255',
            ],
        ];
    }

    public function messages() : array
    {
        return [
            'app_name.required' => 'app_name is required!',
            'project_name.required' => 'github_project is required!',
            'project_name.unique' => 'The github project has already been taken.',
            'app_bundle.required' => 'app_bundle is required!',
            'app_bundle.regex' => 'app_bundle is incorrect! (e.g com.CompanyName.AppName)',
            'appstore_id.required' => 'appstore_id is required!',
        ];
    }
}
