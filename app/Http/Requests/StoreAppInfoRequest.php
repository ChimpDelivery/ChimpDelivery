<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'app_icon' => 'image|mimes:png|max:5120',
            'app_icon_hash' => 'nullable',

            'app_name' => array('required', Rule::unique('app_infos')->ignore($this->route('id'))->whereNull(('deleted_at'))),

            'project_name' => array('required', 'alpha_dash', Rule::unique('app_infos')->ignore($this->route('id'))->whereNull(('deleted_at'))),

            'app_bundle' => array('required', Rule::unique('app_infos')->ignore($this->route('id'))->whereNull(('deleted_at')),
                'regex:/^([a-zA-Z0-9]+\.)+([a-zA-Z0-9]+\.)+([a-zA-Z0-9])/'),

            'appstore_id' => array('required', Rule::unique('app_infos')->ignore($this->route('id'))->whereNull(('deleted_at'))),

            'fb_app_id' => array('nullable', 'numeric', Rule::unique('app_infos')->ignore($this->route('id'))->whereNull('deleted_at')),

            'ga_id' => array('nullable', Rule::unique('app_infos')->ignore($this->route('id'))->whereNull('deleted_at')),

            'ga_secret' => array('nullable', Rule::unique('app_infos')->ignore($this->route('id'))->whereNull('deleted_at'))
        ];
    }

    public function messages() : array
    {
        return [
            'app_name.required' => 'app_name is required!',
            'project_name.required' => 'github_project is required!',
            'app_bundle.required' => 'app_bundle is required!',
            'app_bundle.regex' => 'app_bundle is incorrect! (e.g com.Talus.CozyKitchen)',
            'appstore_id.required' => 'appstore_id is required!',
            'fb_app_id.numeric' => 'fb_app_id is incorrect! (facebook app id contains only numbers)'
        ];
    }
}
