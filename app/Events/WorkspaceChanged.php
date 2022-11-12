<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Workspace;

use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

class WorkspaceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Workspace $workspace;
    public StoreWorkspaceSettingsRequest $request;

    public function __construct(Workspace $workspace, StoreWorkspaceSettingsRequest $request)
    {
        $this->workspace = $workspace;
        $this->request = $request;
    }
}
