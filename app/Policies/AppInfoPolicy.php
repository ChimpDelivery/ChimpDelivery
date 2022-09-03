<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\AppInfo;

class AppInfoPolicy
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
        return $user->can('view apps') && $user->workspace->id !== 1;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param AppInfo $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AppInfo $appInfo)
    {
        return $user->can('view apps')
            && $user->workspace->id === $appInfo->workspace_id
            && $user->workspace->id !== 1;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create app') && $user->workspace->id !== 1;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param AppInfo $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AppInfo $appInfo)
    {
        return $user->can('update app')
            && $user->workspace->id === $appInfo->workspace_id
            && $user->workspace->id !== 1;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param AppInfo $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AppInfo $appInfo)
    {
        return $user->can('delete app')
            && $user->workspace->id === $appInfo->workspace_id
            && $user->workspace->id !== 1;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param AppInfo $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AppInfo $appInfo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param AppInfo $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AppInfo $appInfo)
    {
        //
    }
}
