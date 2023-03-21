<?php

namespace App\Http\Requests\AppStoreConnect;

use Illuminate\Foundation\Http\FormRequest;

class StoreBundleRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'bundle_id' => [ 'required', 'max:255', ],
            'bundle_name' => [ 'required', 'max:255', ],
        ];
    }
}
