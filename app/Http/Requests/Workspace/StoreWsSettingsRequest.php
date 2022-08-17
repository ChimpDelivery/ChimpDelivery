<?php

namespace App\Http\Requests\Workspace;

use Illuminate\Foundation\Http\FormRequest;

class StoreWsSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            'appstore_private_key' => [
                'string',
            ],

            'appstore_issuer_id' => [
                'string',
            ],

            'appstore_kid' => [
                'string',
            ],

            'apple_usermail' => [
                'string',
            ],

            'apple_app_pass' => [
                'string',
            ],

            'github_org_name' => [
                'required',
                'string',
            ],

            'github_access_token' => [
                'required',
                'string',
            ],

            'github_template' => [
                'required',
                'string',
            ],

            'github_topic' => [
                'string',
            ],
        ];
    }
}
