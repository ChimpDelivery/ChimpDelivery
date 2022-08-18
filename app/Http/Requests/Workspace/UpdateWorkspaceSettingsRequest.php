<?php

namespace App\Http\Requests\Workspace;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkspaceSettingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'appstore_private_key' => [ 'nullable', 'string', ],
            'appstore_issuer_id' => [ 'nullable', 'string', ],
            'appstore_kid' => [ 'nullable', 'string', ],

            'apple_usermail' => [ 'nullable', 'string', ],
            'apple_app_pass' => [ 'nullable', 'string', ],

            'github_org_name' => [ 'nullable', 'string', ],
            'github_access_token' => [ 'nullable', 'string', ],
            'github_template' => [ 'nullable', 'string', ],
            'github_topic' => [ 'nullable', 'string', ],
        ];
    }
}
