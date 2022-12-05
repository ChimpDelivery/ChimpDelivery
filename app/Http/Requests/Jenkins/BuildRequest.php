<?php

namespace App\Http\Requests\Jenkins;

use App\Http\Requests\AppInfo\GetAppInfoRequest;

use Illuminate\Validation\Rule;

class BuildRequest extends GetAppInfoRequest
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
            'id' => [
                'required',
                'numeric',
                Rule::exists('app_infos', 'id')->whereNull('deleted_at')
            ],

            'platform' => [
                'required',
                'string',
                Rule::in([ 'Appstore', 'GooglePlay' ])
            ],

            'store_version' => [ 'required', 'numeric' ],
            'store_custom_version' => [ 'nullable', 'string', Rule::in(['true', 'false']) ],
            'store_build_number' => [ 'nullable', 'numeric' ],

            'install_backend' => [ 'nullable', 'string', Rule::in(['on']) ],
        ];
    }
}
