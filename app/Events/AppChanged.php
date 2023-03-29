<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Http\Request;

use App\Models\AppInfo;

class AppChanged
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public function __construct(
        public readonly AppInfo $appInfo,
        public readonly Request $request
    ) {
    }
}
