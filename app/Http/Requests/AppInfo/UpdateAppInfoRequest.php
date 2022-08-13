<?php

namespace App\Http\Requests\AppInfo;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Auth;

class UpdateAppInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return
        [
            'id' =>
            [
                'required',
                'numeric',
                Rule::exists('app_infos', 'id')->whereNull('deleted_at'),
            ],

            'app_icon' => 'image|mimes:png|max:5120',

            'fb_app_id' =>
            [
                'nullable',
                'numeric',
                Rule::unique('app_infos')->ignore($this->id)->whereNull('deleted_at'),
            ],

            'ga_id' =>
            [
                'nullable',
                Rule::unique('app_infos')->ignore($this->id)->whereNull('deleted_at'),
            ],

            'ga_secret' =>
            [
                'nullable',
                Rule::unique('app_infos')->ignore($this->id)->whereNull('deleted_at'),
            ]
        ];
    }
}
