<?php

namespace App\Actions\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;

class CreateUserApiKey
{
    use AsAction;

    public function handle() : string
    {
        return Auth::user()->createApiToken();
    }
}
