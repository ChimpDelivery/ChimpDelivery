<?php

namespace App\Actions\Dashboard\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use App\Actions\Api\S3\Provision\GetProvisionProfile;

class GetWorkspaceForm
{
    use AsAction;

    public function handle() : View
    {
        $workspace = Auth::user()->workspace;

        $provisionExpire = GetProvisionProfile::run()
            ->headers
            ->get(config('appstore-sign.provision.required_tags')['expire']['web']);

        return view('workspace-settings')->with([
            'isNew' => false,
            'workspace' => $workspace,
            'cert_label' => $workspace->appstoreConnectSign->cert_name ?: 'Choose...',
            'provision_label' => "Expire Date: {$provisionExpire}" ?: 'Choose...'
        ]);
    }
}
