<?php

namespace App\Http\Requests\Jenkins;

use Illuminate\Validation\Rule;

class StopJobRequest extends GetJobRequest
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
            'id' => array('required', 'numeric', Rule::exists('app_infos', 'id')->whereNull('deleted_at')),
            'build_number' => array('required', 'numeric')
        ];
    }
}
