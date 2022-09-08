<?php

namespace App\Http\Requests\Package;

use Illuminate\Validation\Rule;

class UpdatePackageRequest extends GetPackageRequest
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
                'regex:/^[A-Za-z.]+$/',
                Rule::exists('packages', 'package_id')
            ],

            'hash' => [
                'required',
                'alpha_num'
            ]
        ];
    }

    public function messages() : array
    {
        return [
            'package_id.required' => 'package_id is required!',
            'hash.required' => 'hash is required!',
            'hash.alpha_num' => 'hash can only contains alpha-numeric characters!'
        ];
    }
}
