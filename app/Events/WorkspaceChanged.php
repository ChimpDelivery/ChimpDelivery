<?php

namespace App\Events;

use App\Http\Requests\Workspace\StoreWorkspaceSettingsRequest;

use App\Models\Workspace;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkspaceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $workspace;
    public $request;

    public function __construct(Workspace $workspace, StoreWorkspaceSettingsRequest $request)
    {
        $this->workspace = $workspace;
        $this->request = $request;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
