<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\AppStoreConnectSetting;

class AppStoreConnectSettingSeeder extends Seeder
{
    public function run()
    {
        // related to default workspace
        AppStoreConnectSetting::factory()->create([
            'workspace_id' => 1,
            'private_key' => null,
            'issuer_id' => null,
            'kid' => null,
        ]);

        AppStoreConnectSetting::factory()->create([
            'workspace_id' => 2,
        ]);

        AppStoreConnectSetting::factory()->create([
            'workspace_id' => 3,
        ]);
    }
}
