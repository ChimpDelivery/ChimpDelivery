<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

use App\Models\Workspace;

class WorkspaceObserver
{
    public function created(Workspace $workspace) : void
    {
        $user = Auth::user();
        if ($user->update([ 'workspace_id' => $workspace->id ]))
        {
            $user->syncRoles([ 'Admin_Workspace' ]);
        }
    }

    public function updated(Workspace $workspace) : void
    {
        //
    }

    public function deleted(Workspace $workspace) : void
    {
        //
    }

    public function restored(Workspace $workspace) : void
    {
        //
    }

    public function forceDeleted(Workspace $workspace) : void
    {
        //
    }
}
