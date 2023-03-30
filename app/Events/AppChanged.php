<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\AppInfo;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;

class AppChanged
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public function __construct(
        public readonly AppInfo $appInfo,
        public readonly StoreAppInfoRequest $request
    ) {
    }
}
