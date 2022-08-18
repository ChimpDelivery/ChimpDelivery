<?php

namespace App\Http\Requests\Github;

use App\Rules\AlphaDashDot;

use Illuminate\Foundation\Http\FormRequest;

class GetRepositoryRequest extends FormRequest
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
            'project_name' => [ 'required', new AlphaDashDot() ]
        ];
    }
}
