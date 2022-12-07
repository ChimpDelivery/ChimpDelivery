<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class GetWorkspaceForm
{
    use AsAction;

    public function handle() : View
    {
        $workspace = Auth::user()->workspace;
        $wsSign = $workspace->appstoreConnectSign;

        return view('workspace-settings')->with([
            'isNew' => false,
            'workspace' => $workspace,
            'cert_label' => Str::of($wsSign->cert ?? 'Choose...')
                ->explode('/')
                ->last(),
            'provision_label' => Str::of($wsSign->provision_profile ?? 'Choose...')
                ->explode('/')
                ->last(),
        ]);
    }
}
