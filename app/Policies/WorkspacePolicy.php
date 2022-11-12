<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Workspace $workspace)
    {
        return $user->can('view workspace') && $user->workspace->id === $workspace->id;
    }

    public function create(User $user)
    {
        return $user->can('create workspace') && $user->workspace->id === Workspace::$DEFAULT_WORKSPACE_ID;
    }

    public function join(User $user, Workspace $workspace)
    {
        return $user->can('join workspace')
            && $user->workspace->id === Workspace::$DEFAULT_WORKSPACE_ID
            && $workspace->id !== Workspace::$DEFAULT_WORKSPACE_ID;
    }

    public function update(User $user, Workspace $workspace)
    {
        return $user->can('update workspace')
            && $user->workspace->id === $workspace->id
            && $user->workspace->id !== Workspace::$DEFAULT_WORKSPACE_ID;
    }

    public function delete(User $user, Workspace $workspace)
    {
        //
    }

    public function restore(User $user, Workspace $workspace)
    {
        //
    }

    public function forceDelete(User $user, Workspace $workspace)
    {
        //
    }
}
