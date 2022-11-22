<?php

namespace App\Events;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\AppInfo;

class AppChanged
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Request $request;
    public AppInfo $appInfo;

    public function __construct(AppInfo $appInfo, Request $request)
    {
        $this->request = $request;
        $this->appInfo = $appInfo;
    }
}
