<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

use App\Models\User;
use App\Models\Workspace;
use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

class WorkspaceChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly User $workspaceAdmin,
        public readonly Workspace $workspace,
        public readonly StoreWorkspaceSettingsRequest $request
    ) {
    }
}
