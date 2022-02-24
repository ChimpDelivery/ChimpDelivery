<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppInfoRequest extends FormRequest
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
            'app_icon' => 'required|image|mimes:png|max:1024',
            'app_name' => 'required',
            'app_bundle' => array('required', 'regex:/^([a-zA-Z0-9]+\.)+([a-zA-Z0-9]+\.)+([a-zA-Z0-9])/', 'unique:app_infos'),
            'fb_app_id' => 'required|numeric|unique:app_infos',
            'elephant_id' => 'required|unique:app_infos',
            'elephant_secret' => 'required|unique:app_infos'
        ];
    }

    public function messages() : array
    {
        return [
            'app_bundle.required' => 'app_bundle is required!',
            'app_bundle.regex' => 'app_bundle is incorrect! (e.g com.Talus.TalusTemplateURP)',
            'fb_app_id.required' => 'fb_app_id is required!',
            'fb_app_id.numeric' => 'fb_app_id is incorrect!',
            'elephant_id.required' => 'elephant_id is required!',
            'elephant_secret.required' => 'elephant_secret is required!'
        ];
    }
}
