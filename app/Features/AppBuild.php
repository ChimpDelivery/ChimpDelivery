<?php

namespace App\Features;

use App\Models\User;

// who can build app in dashboard.
// only internal talus users for now...
class AppBuild
{
    public function resolve(User $user) : bool
    {
        return match (true)
        {
            $user->isInternal() => true,
            default => false,
        };
    }
}
