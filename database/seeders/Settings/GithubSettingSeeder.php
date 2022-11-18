<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\GithubSetting;

class GithubSettingSeeder extends Seeder
{
    public function run()
    {
        // related to default workspace
        GithubSetting::factory()->create([
            'workspace_id' => 1,
            'personal_access_token' => null,
            'organization_name' => 'default-organization',
            'template_name' => null,
            'topic_name' => null,
        ]);

        foreach (range(2, 5) as $id)
        {
            GithubSetting::factory()->create([
                'workspace_id' => $id,
            ]);
        }
    }
}
