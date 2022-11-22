<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class GetPackageRequest extends FormRequest
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
            'package_id' => [
                'required',
                'regex:/^[A-Za-z0-9._-]+$/',
                'max:255',
                Rule::exists('packages', 'package_id')
            ]
        ];
    }

    public function messages() : array
    {
        return [
            'package_id.required' => 'package_id is required!',
        ];
    }
}
