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

            'private_key' => [ 'nullable', 'string' ],
            'issuer_id' => [ 'nullable', 'alpha_dash' ],
            'kid' => [ 'nullable', 'alpha_dash' ],

            'usermail' => [ 'nullable', 'email:rfc' ],
            'app_specific_pass' => [ 'nullable', 'string' ],

            'organization_name' => [ 'nullable', new AlphaDashDot() ],
            'personal_access_token' => [ 'nullable', 'alpha_num' ],
            'template_name' => [ 'nullable', new AlphaDashDot() ],
            'topic_name' => [ 'nullable', new AlphaDashDot() ],

            'api_key' => [ 'nullable', 'alpha_num' ],
        ];
    }
}
