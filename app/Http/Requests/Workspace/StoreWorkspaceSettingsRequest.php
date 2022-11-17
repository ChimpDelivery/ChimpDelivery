<?php

namespace App\Http\Requests\Workspace;

use App\Rules\AlphaDashDot;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;

class StoreWorkspaceSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'name' => [
                'required',
                'max:255',
                'alpha_num',
                Rule::unique('workspaces')
                    ->ignore(Auth::user()->workspace->id, 'id')
                    ->whereNull('deleted_at'),
            ],

            'private_key' => [
                'nullable',
                'mimetypes:text/plain',
                'max:1'
            ],

            'issuer_id' => [
                'nullable',
                'alpha_dash',
                'max:255',
            ],

            'kid' => [
                'nullable',
                'alpha_dash',
                'max:255',
            ],

            'usermail' => [
                'nullable',
                'email',
                'max:255',
            ],

            'app_specific_pass' => [
                'nullable',
                'string',
                'max:255',
            ],

            'organization_name' => [
                'required',
                new AlphaDashDot(),
                'max:255',
                Rule::unique('github_settings')
                    ->ignore(Auth::user()->workspace->id, 'workspace_id')
                    ->whereNull('deleted_at')
            ],

            'personal_access_token' => [
                'nullable',
                'alpha_dash',
                'max:255',
            ],

            'template_name' => [
                'nullable',
                new AlphaDashDot(),
                'max:255',
            ],

            'topic_name' => [
                'nullable',
                new AlphaDashDot(),
                'max:255',
            ],
        ];
    }

    public function messages() : array
    {
        return [
            'organization_name.required' => 'Github API ➔ Organization Name field is required.',
            'organization_name.unique' => 'Github API ➔ Organization Name has already linked to another workspace.',
        ];
    }
}
