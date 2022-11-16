<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class GetWorkspaceForm
{
    use AsAction;

    public function handle() : View
    {
        return view('workspace-settings')->with([
            'workspace' => Auth::user()->workspace,
            'isNew' => false,
        ]);
    }
}
