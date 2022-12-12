<?php

namespace App\Actions\Dashboard;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

use App\Actions\Dashboard\Workspace\GetWorkspaceIndex;

class GetIndexForm
{
    use AsAction;

    public function handle() : View
    {
        return Auth::user()->isNew() ? GetNewUserIndex::run() : GetWorkspaceIndex::run();
    }
}
