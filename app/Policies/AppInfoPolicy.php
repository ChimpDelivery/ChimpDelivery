<?php

namespace App\Policies;

use App\Models\AppInfo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppInfoPolicy
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
     * @param  \App\Models\AppInfo  $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, AppInfo $appInfo)
    {
        if ($user->can('view app')) {
            return $user->workspace->id == $appInfo->workspace_id;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create app');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppInfo  $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, AppInfo $appInfo)
    {
        if ($user->can('update app')) {
            return $user->workspace->id == $appInfo->workspace_id;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppInfo  $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, AppInfo $appInfo)
    {
        if ($user->can('delete app')) {
            return $user->workspace_id == $appInfo->workspace_id;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppInfo  $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, AppInfo $appInfo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppInfo  $appInfo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, AppInfo $appInfo)
    {
        //
    }
}
