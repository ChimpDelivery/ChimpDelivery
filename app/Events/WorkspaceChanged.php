<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\User;
use App\Models\Workspace;
use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

class WorkspaceChanged
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public function __construct(
        public readonly User $workspaceAdmin,
        public readonly Workspace $workspace,
        public readonly StoreWorkspaceSettingsRequest $request
    ) {
    }
}
