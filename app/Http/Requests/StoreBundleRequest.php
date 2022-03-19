<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBundleRequest extends FormRequest
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
    public function rules()
    {
        return [
            'bundle_id' => array('required', 'alpha_num'),
            'bundle_name' => array('required', 'regex:/^([a-zA-Z0-9 ]*$)/'),
        ];
    }

    public function messages() : array
    {
        return [
            'bundle_id.alpha_num' => 'Bundle id can only contains alpha-numeric characters!',
            'bundle_name.regex' => 'Bundle name can only contains alpha-numeric characters and space!'
        ];
    }
}
