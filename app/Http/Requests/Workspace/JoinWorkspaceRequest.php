<?php

namespace App\Http\Requests\Workspace;

use Illuminate\Foundation\Http\FormRequest;

class JoinWorkspaceRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'invite_code' => [
                'required',
                'alpha_num',
                'max:64',
            ],
        ];
    }
}
