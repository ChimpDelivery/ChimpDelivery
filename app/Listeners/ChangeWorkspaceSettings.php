<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;

use App\Events\WorkspaceChanged;

class ChangeWorkspaceSettings
{
    public function __construct()
    { }

    public function handle(WorkspaceChanged $event)
    {
        $targetWorkspace = $event->workspace;
        $validated = $event->request->safe();

        $targetWorkspace->fill($validated->only([ 'name' ]));
        $targetWorkspace->save();

        if ($targetWorkspace->wasRecentlyCreated)
        {
            Auth::user()->update([ 'workspace_id' => $targetWorkspace->id ]);
            Auth::user()->syncRoles([ 'Admin_Workspace' ]);
        }

        // 1. app store connect
        $appStoreConnectSetting = $targetWorkspace->appStoreConnectSetting()->firstOrCreate();
        if ($event->request->hasFile('private_key')) {
            $appStoreConnectSetting->update([ 'private_key' => $validated->private_key->get() ]);
        }
        $appStoreConnectSetting->update($validated->only([
            'issuer_id',
            'kid',
        ]));

        // 2. apple
        $appleSetting = $targetWorkspace->appleSetting()->firstOrCreate();
        $appleSetting->update($validated->only([
            'usermail',
            'app_specific_pass',
        ]));

        // 3. github
        $githubSetting = $targetWorkspace->githubSetting()->firstOrCreate([
            'organization_name' => ($targetWorkspace->wasRecentlyCreated)
                ? $validated->organization_name
                : $targetWorkspace->githubSetting->organization_name,
        ]);
        $githubSetting->update($validated->only([
            'personal_access_token',
            'template_name',
            'topic_name',
            'public_repo',
            'private_repo',
        ]));
    }
}
