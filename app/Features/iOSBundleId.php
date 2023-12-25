<?php

namespace App\Features;

use App\Models\User;

class iOSBundleId
{
    public function resolve(User $user) : bool
    {
        return match (true)
        {
            // $user->isInternal() => true,
            default => true,
        };
    }
}
