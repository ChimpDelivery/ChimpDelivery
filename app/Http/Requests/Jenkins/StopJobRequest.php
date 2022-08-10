<?php

namespace App\Http\Requests\Jenkins;

use Illuminate\Foundation\Http\FormRequest;

class StopJobRequest extends FormRequest
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
            'projectName' => array('required', 'alpha_dash'),
            'buildNumber' => array('required', 'numeric')
        ];
    }
}
