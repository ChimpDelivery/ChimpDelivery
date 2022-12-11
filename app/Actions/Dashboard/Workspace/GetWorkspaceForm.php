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
        $workspace = Auth::user()->workspace;
        $wsSign = $workspace->appstoreConnectSign;

        return view('workspace-settings')->with([
            'isNew' => false,
            'workspace' => $workspace,
            'cert_label' => $wsSign->cert_name ?: 'Choose...',
            'provision_label' => $wsSign->provision_name ?: 'Choose...'
        ]);
    }
}
