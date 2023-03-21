<?php

namespace App\Http\Requests\AppInfo;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class GetAppInfoRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'id' => [
                'required',
                'numeric',
                Rule::exists('app_infos', 'id')
                    ->where('workspace_id', Auth::user()->workspace->id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages() : array
    {
        return [
            'id.required' => 'id is required!',
            'id.numeric' => 'id can only contains numerics!'
        ];
    }
}
