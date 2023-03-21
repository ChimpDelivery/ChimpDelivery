<?php

namespace App\Http\Requests\Jenkins;

use Illuminate\Validation\Rule;

use App\Actions\Api\Jenkins\JobPlatform;
use App\Http\Requests\AppInfo\GetAppInfoRequest;

class BuildRequest extends GetAppInfoRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return array_merge(parent::rules(), [
            'platform' => [
                'required',
                'string',
                Rule::in(JobPlatform::GetActivePlatforms())
            ],

            'store_version' => [ 'required', 'numeric' ],
            'store_custom_version' => [ 'nullable', 'string', Rule::in(['true', 'false']) ],
            'store_build_number' => [ 'nullable', 'numeric' ],

            'install_backend' => [ 'nullable', 'string', Rule::in(['on']) ],
        ]);
    }
}
