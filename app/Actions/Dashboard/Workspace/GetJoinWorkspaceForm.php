<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class GetJoinWorkspaceForm
{
    use AsAction;

    public function handle() : View
    {
        return view('workspace-join');
    }

    public function authorize() : bool
    {
        return Auth::user()->isNew();
    }
}
