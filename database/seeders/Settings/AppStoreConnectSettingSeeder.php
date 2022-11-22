<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\AppStoreConnectSetting;

class AppStoreConnectSettingSeeder extends Seeder
{
    public function run()
    {
        foreach (range(1, 5) as $id)
        {
            AppStoreConnectSetting::factory()->create([
                'workspace_id' => $id,
            ]);
        }
    }
}
