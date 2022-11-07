<?php

namespace App\Http\Requests\Workspace;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class JoinWorkspaceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'invite_code' => [
                'required',
                'alpha_num',
                Rule::exists('workspace_invite_codes', 'code'),
            ]
        ];
    }
}
