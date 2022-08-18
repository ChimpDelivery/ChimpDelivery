<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\GithubSetting;

class GithubSettingSeeder extends Seeder
{
    public function run()
    {
        GithubSetting::factory()->create([
            'workspace_id' => 1,
        ]);

        GithubSetting::factory()->create([
            'workspace_id' => 2,
        ]);

        GithubSetting::factory()->create([
            'workspace_id' => 3,
        ]);
    }
}
