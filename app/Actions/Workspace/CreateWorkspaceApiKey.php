<?php

namespace App\Actions\Workspace;

use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateWorkspaceApiKey
{
    use AsAction;

    public function handle() : string
    {
        return Auth::user()->workspace->createApiToken();
    }
}
