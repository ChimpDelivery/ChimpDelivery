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
                'alpha_num',
                Rule::unique('workspaces')
                    ->ignore(Auth::user()->workspace->id, 'id')
                    ->whereNull('deleted_at'),
            ],

            'private_key' => [ 'nullable', 'mimetypes:text/plain', 'max:1' ],
            'issuer_id' => [ 'nullable', 'alpha_dash' ],
            'kid' => [ 'nullable', 'alpha_dash' ],

            'usermail' => [ 'nullable', 'email' ],
            'app_specific_pass' => [ 'nullable', 'string' ],

            'organization_name' => [
                'nullable',
                new AlphaDashDot(),
                Rule::unique('github_settings')->whereNull('deleted_at'),
            ],
            'personal_access_token' => [ 'nullable', 'alpha_dash' ],
            'template_name' => [ 'nullable', new AlphaDashDot() ],
            'topic_name' => [ 'nullable', new AlphaDashDot() ],
        ];
    }
}
