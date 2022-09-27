<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\AppInfo;

class AppInfoPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('view apps') && $user->workspace->id !== 1;
    }

    public function view(User $user, AppInfo $appInfo)
    {
        return $user->can('view apps')
            && $user->workspace->id === $appInfo->workspace_id
            && $user->workspace->id !== 1;
    }

    public function create(User $user)
    {
        return $user->can('create app') && $user->workspace->id !== 1;
    }

    public function update(User $user, AppInfo $appInfo)
    {
        return $user->can('update app')
            && $user->workspace->id === $appInfo->workspace_id
            && $user->workspace->id !== 1;
    }

    public function delete(User $user, AppInfo $appInfo)
    {
        return $user->can('delete app')
            && $user->workspace->id === $appInfo->workspace_id
            && $user->workspace->id !== 1;
    }

    public function build(User $user, AppInfo $appInfo)
    {
        return $user->can('build job')
            && $user->workspace->id === $appInfo->workspace_id
            && $user->workspace->id !== 1;
    }

    public function abort(User $user, AppInfo $appInfo)
    {
        return $user->can('abort job')
            && $user->workspace->id === $appInfo->workspace_id
            && $user->workspace->id !== 1;
    }


    public function restore(User $user, AppInfo $appInfo)
    {
        //
    }

    public function forceDelete(User $user, AppInfo $appInfo)
    {
        //
    }
}
