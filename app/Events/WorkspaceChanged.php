<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\ValidatedInput;

class WorkspaceChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Workspace $workspace,
        public readonly ValidatedInput $inputs
    ) {
    }
}
