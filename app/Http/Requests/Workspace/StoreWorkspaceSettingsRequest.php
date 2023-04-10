<?php

namespace App\Http\Requests\Workspace;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use App\Rules\CorrectMime;
use App\Rules\AlphaDashDot;

class StoreWorkspaceSettingsRequest extends FormRequest
{
    protected function prepareForValidation() : void
    {
        $this->merge(['public_repo' => $this->has('public_repo')]);
        $this->merge(['private_repo' => $this->has('private_repo')]);
    }

    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        return [

            'name' => [
                'required',
                'max:12',
                'alpha_num',
                Rule::unique('workspaces')
                    ->ignore(Auth::user()->workspace->id)
                    ->whereNull('deleted_at'),
            ],

            'private_key' => [ 'nullable', 'mimetypes:text/plain', 'max:1', ],
            'issuer_id' => [ 'nullable', 'alpha_dash', 'max:255', ],
            'kid' => [ 'nullable', 'alpha_dash', 'max:255', ],

            'cert' => [
                'nullable',
                'max:64',
                new CorrectMime('application/octet-stream', 'application/x-pkcs12', '.p12')
            ],

            'provision_profile' => [
                'nullable',
                'max:64',
                new CorrectMime('application/octet-stream', 'application/octet-stream', '.mobileprovision')
            ],

            'usermail' => [ 'nullable', 'email', 'max:255', ],
            'app_specific_pass' => [ 'nullable', 'string', 'max:255', ],

            'service_account' => [
                'nullable',
                'max:8',
                new CorrectMime('application/json', 'application/json', '.json'),
            ],

            'keystore_file' => [
                'nullable',
                'max:8',
                new CorrectMime('application/x-java-keystore', 'application/octet-stream', '.keystore'),
            ],

            'keystore_pass' => [ 'nullable', 'string', 'max:255', ],

            'organization_name' => [
                'nullable',
                new AlphaDashDot(),
                'max:255',
                Rule::notIn([ config('workspaces.default_org_name') ]),
                Rule::unique('github_settings')
                    ->ignore(Auth::user()->workspace->id, 'workspace_id')
                    ->whereNull('deleted_at'),
            ],

            'personal_access_token' => [ 'nullable', 'alpha_dash', 'max:255', ],
            'template_name' => [ 'nullable', new AlphaDashDot(), 'max:255', ],
            'topic_name' => [ 'nullable', new AlphaDashDot(), 'max:255', ],
            'public_repo' => [ 'required', 'boolean', ],
            'private_repo' => [ 'required', 'boolean', ],
        ];
    }

    public function messages() : array
    {
        return [
            'name.alpha_num' => 'Workspace Name must only contain letters and numbers.',
            'name.max' => 'Workspace Name must not be greater than 12 characters.',
            'organization_name.unique' => 'Github API ➔ Organization Name has already linked to another Workspace.',
        ];
    }
}
