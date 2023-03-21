<?php

namespace App\Http\Requests\Github;

use App\Rules\AlphaDashDot;

use Illuminate\Foundation\Http\FormRequest;

class GetRepositoryRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'project_name' => [
                'required',
                new AlphaDashDot(),
                'max:255',
            ],
        ];
    }
}
