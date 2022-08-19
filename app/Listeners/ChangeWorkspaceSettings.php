<?php

namespace App\Listeners;

use App\Events\WorkspaceChanged;

use App\Models\AppleSetting;
use App\Models\AppStoreConnectSetting;
use App\Models\GithubSetting;

use Illuminate\Support\Facades\Auth;

class ChangeWorkspaceSettings
{
    public function __construct()
    { }

    public function handle(WorkspaceChanged $event)
    {
        $targetWorkspace = $event->workspace;
        $validated = $event->request->safe();

        $targetWorkspace->fill($validated->only([ 'name', 'api_key' ]));
        $targetWorkspace->save();

        if ($targetWorkspace->wasRecentlyCreated)
        {
            Auth::user()->update([ 'workspace_id' => $targetWorkspace->id ]);
            Auth::user()->syncRoles([ 'Admin_Workspace' ]);
        }

        // 1. app store connect
        $appStoreConnectSetting = AppStoreConnectSetting::firstOrCreate([ 'workspace_id' => $targetWorkspace->id ]);
        if ($event->request->hasFile('private_key')) {
            $appStoreConnectSetting->update([ 'private_key' => $validated->private_key->get() ]);
        }
        $appStoreConnectSetting->update($validated->only([
            'workspace_id',
            'issuer_id',
            'kid',
        ]));

        // 2. apple
        $appleSetting = AppleSetting::firstOrCreate([ 'workspace_id' => $targetWorkspace->id ]);
        $appleSetting->update($validated->only([
            'workspace_id',
            'usermail',
            'app_specific_pass',
        ]));

        // 3. github
        $githubSetting = GithubSetting::firstOrCreate([ 'workspace_id' => $targetWorkspace->id ]);
        $githubSetting->update($validated->only([
            'workspace_id',
            'personal_access_token',
            'organization_name',
            'template_name',
            'topic_name',
        ]));
    }
}
