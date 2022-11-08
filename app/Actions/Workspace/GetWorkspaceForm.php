<?php

namespace App\Actions\Workspace;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;

use App\Http\Controllers\Api\WorkspaceController;

class GetWorkspaceForm
{
    use AsAction;

    public function handle() : View
    {
        $workspace = app(WorkspaceController::class)->Get();

        return view('workspace-settings')->with([
            'workspace' => $workspace,
            'isNew' => false,
        ]);
    }
}
