<?php

namespace App\Actions\Dashboard\User;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;

class CreateUserApiKey
{
    use AsAction;

    public function handle() : string
    {
        return Auth::user()->createApiToken();
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew() && Auth::user()->can('create api token');
    }
}
