<?php

namespace App\Listeners;

use App\Events\WorkspaceChanged;

use Laravel\Pennant\Feature;

use App\Features\AppBuild;
use App\Jobs\Jenkins\CreateOrganization;

class UpdateWorkspaceSettings
{
    public function handle(WorkspaceChanged $event) : void
    {
        $workspace = $event->workspace;
        $validated = $event->inputs;

        // AppStoreConnect
        $appStoreConnectSetting = $workspace->appStoreConnectSetting()->firstOrCreate();
        if ($validated->has('private_key'))
        {
            $appStoreConnectSetting->fill(['private_key' => $validated->private_key->get()]);
        }
        $appStoreConnectSetting->fill($validated->only(['issuer_id', 'kid']));
        $appStoreConnectSetting->save();

        // AppleSetting
        $appleSetting = $workspace->appleSetting()->firstOrCreate();
        $appleSetting->fill($validated->only(['usermail', 'app_specific_pass']));
        $appleSetting->save();

        // GooglePlaySetting
        $googlePlaySetting = $workspace->googlePlaySetting()->firstOrCreate();
        $googlePlaySetting->fill($validated->only(['android_keystore_pass']));
        $googlePlaySetting->save();

        // GithubSetting
        $githubSetting = $workspace->githubSetting()->firstOrCreate();
        $githubSetting->fill([
            'organization_name' => empty($workspace->githubSetting->organization_name)
                ? $validated->organization_name ?? null
                : $workspace->githubSetting->organization_name,
        ]);
        $githubSetting->fill($validated->only([
            'personal_access_token',
            'template_name',
            'topic_name',
            'public_repo',
            'private_repo',
        ]));
        $githubSetting->save();

        CreateOrganization::dispatchIf(
            Feature::for($event->user)->active(AppBuild::class),
            $workspace,
            $event->user
        );
    }
}
