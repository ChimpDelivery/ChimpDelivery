<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

use App\Models\AppInfo;
use App\Http\Requests\AppInfo\StoreAppInfoRequest;

class AppChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly AppInfo $appInfo,
        public readonly StoreAppInfoRequest $request
    ) {
    }
}
