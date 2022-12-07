<?php

namespace App\Http\Requests\Jenkins;

use App\Http\Requests\AppInfo\GetAppInfoRequest;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StopJobRequest extends GetAppInfoRequest
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
                Rule::exists('app_infos', 'id')
                    ->where('workspace_id', Auth::user()->workspace->id)
                    ->whereNull('deleted_at')
            ],

            'build_number' => [ 'required', 'numeric' ]
        ];
    }
}
