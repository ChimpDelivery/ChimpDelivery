<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function ViewWorkspace(User $user, Workspace $workspace)
    {
        return $user->workspace_id === $workspace->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Workspace $workspace)
    {
        return $user->workspace_id === $workspace->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workspace  $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Workspace $workspace)
    {
        //
    }
}
