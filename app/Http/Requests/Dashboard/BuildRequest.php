<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class BuildRequest extends FormRequest
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
            'id' => array('required', 'numeric'),
            'platform' => array('required', 'alpha_num'),
            'storeVersion' => array('required', 'numeric'),
            'storeCustomVersion' => array('nullable', 'string', Rule::in(['true', 'false'])),
            'storeBuildNumber' => array('nullable', 'numeric')
        ];
    }
}
