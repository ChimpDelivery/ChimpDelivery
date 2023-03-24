<?php

namespace App\Features;

use App\Models\User;

class BranchBasedBuild
{
    public function resolve(User $user) : bool
    {
        return match (true) {
            !$user->isNew() => true,
            $user->isWorkspaceAdmin() => true,
            $user->isSuperAdmin() => false,
            default => false,
        };
    }
}
