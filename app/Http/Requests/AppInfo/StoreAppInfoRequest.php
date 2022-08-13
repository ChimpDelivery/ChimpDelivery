<?php

namespace App\Http\Requests\AppInfo;

use App\Http\Requests\Github\GetRepositoryRequest;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;

class StoreAppInfoRequest extends GetRepositoryRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true; // Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'app_icon' => 'image|mimes:png|max:5120',

            'app_name' =>
            [
                'required',
                Rule::unique('app_infos')->whereNull('deleted_at'),
            ],

            'project_name' =>
            [
                'required',
                'alpha_dash',
                Rule::unique('app_infos')->whereNull('deleted_at'),
            ],

            'app_bundle' =>
            [
                'required',
                'regex:/^[a-z][a-z0-9_]*(\.[a-z0-9_]+)+[0-9a-z_]$/i',
                Rule::unique('app_infos')->whereNull('deleted_at'),
            ],

            'appstore_id' =>
            [
                'required',
                Rule::unique('app_infos')->whereNull('deleted_at'),
            ],

            'fb_app_id' =>
            [
                'nullable',
                'numeric',
            ],

            'ga_id' =>
            [
                'nullable',
                'string',
            ],

            'ga_secret' =>
            [
                'nullable',
                'string',
            ]
        ];
    }

    public function messages() : array
    {
        return [
            'app_name.required' => 'app_name is required!',
            'project_name.required' => 'github_project is required!',
            'app_bundle.required' => 'app_bundle is required!',
            'app_bundle.regex' => 'app_bundle is incorrect! (e.g com.CompanyName.AppName)',
            'appstore_id.required' => 'appstore_id is required!',
            'fb_app_id.numeric' => 'fb_app_id is incorrect! (facebook app id contains only numbers)'
        ];
    }
}
