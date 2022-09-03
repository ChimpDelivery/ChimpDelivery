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
     * @param User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Workspace $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Workspace $workspace)
    {
        return $user->can('view workspace') && $user->workspace->id === $workspace->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create workspace') && $user->workspace->id === 1;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Workspace $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Workspace $workspace)
    {
        return $user->can('update workspace')
            && $user->workspace->id === $workspace->id
            && $user->workspace->id !== 1;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Workspace $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Workspace $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Workspace $workspace)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Workspace $workspace
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Workspace $workspace)
    {
        //
    }
}
