<?php

namespace App\Http\Requests\Workspace;

use App\Rules\AlphaDashDot;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

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
                Rule::unique('workspaces')->whereNull('deleted_at')
            ],

            'appstore_private_key' => [ 'nullable', 'string' ],
            'appstore_issuer_id' => [ 'nullable', 'alpha_dash' ],
            'appstore_kid' => [ 'nullable', 'alpha_dash' ],

            'apple_usermail' => [ 'nullable', 'email:rfc' ],
            'apple_app_pass' => [ 'nullable', 'string' ],

            'github_org_name' => [ 'nullable', new AlphaDashDot() ],
            'github_access_token' => [ 'nullable', 'alpha_num' ],
            'github_template' => [ 'nullable', new AlphaDashDot() ],
            'github_topic' => [ 'nullable', new AlphaDashDot() ],

            'api_key' => [ 'nullable', 'alpha_num' ],
        ];
    }
}
