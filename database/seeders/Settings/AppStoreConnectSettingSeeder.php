<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\AppStoreConnectSetting;

class AppStoreConnectSettingSeeder extends Seeder
{
    public function run()
    {
        AppStoreConnectSetting::factory()->create([
            'workspace_id' => 1,
        ]);

        AppStoreConnectSetting::factory()->create([
            'workspace_id' => 2,
        ]);

        AppStoreConnectSetting::factory()->create([
            'workspace_id' => 3,
        ]);
    }
}
