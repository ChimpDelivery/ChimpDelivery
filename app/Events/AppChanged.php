<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\ValidatedInput;

use App\Models\AppInfo;

class AppChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly AppInfo $appInfo,
        public readonly ValidatedInput $inputs
    ) {
    }
}
