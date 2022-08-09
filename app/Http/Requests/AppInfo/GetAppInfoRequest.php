<?php

namespace App\Http\Requests\AppInfo;

use Illuminate\Foundation\Http\FormRequest;

class GetAppInfoRequest extends FormRequest
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
            'id' => array('required', 'numeric')
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
