<?php

namespace App\Http\Requests\AppStoreConnect;

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
            'bundle_id' => [ 'required', 'max:255', ],
            'bundle_name' => [ 'required', 'max:255', ],
        ];
    }

    public function messages() : array
    {
        return [

        ];
    }
}
