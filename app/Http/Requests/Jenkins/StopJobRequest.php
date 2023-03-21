<?php

namespace App\Http\Requests\Jenkins;

use App\Http\Requests\AppInfo\GetAppInfoRequest;

class StopJobRequest extends GetAppInfoRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return array_merge(parent::rules(), [
            'build_number' => [
                'required',
                'numeric',
            ],
        ]);
    }
}
