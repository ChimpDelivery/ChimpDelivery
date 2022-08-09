<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
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
            'package_id' => array('required', 'regex:/^([a-zA-Z0-9]+\.)+([a-zA-Z0-9]+\.)+([a-zA-Z0-9])/'),
            'hash' => array('required')
        ];
    }

    public function messages() : array
    {
        return [
            'package_id.required' => 'package_id is required!',
            'package_id.regex' => 'package_id is incorrect! (e.g com.talus.talusci)',
            'hash.required' => 'hash is required!'
        ];
    }
}
