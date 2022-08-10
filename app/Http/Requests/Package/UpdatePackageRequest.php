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
            'package_id' => array('required', 'regex:/^[a-z][a-z0-9_]*(\.[a-z0-9_]+)+[0-9a-z_]$/i'),
            'hash' => array('required', 'alpha_num')
        ];
    }

    public function messages() : array
    {
        return [
            'package_id.required' => 'package_id is required!',
            'package_id.regex' => 'package_id is incorrect! (e.g com.CompanyName.AppName)',
            'hash.required' => 'hash is required!',
            'hash.alpha_num' => 'hash can only contains alpha-numeric characters!'
        ];
    }
}
