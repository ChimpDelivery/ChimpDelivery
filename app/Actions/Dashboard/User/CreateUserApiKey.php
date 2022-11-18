<?php

namespace App\Actions\Dashboard\User;

use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUserApiKey
{
    use AsAction;

    public function handle() : string
    {
        return Auth::user()->createApiToken();
    }
}
