<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\AppleSetting;

class AppleSettingSeeder extends Seeder
{
    public function run()
    {
        // related to default workspace
        AppleSetting::factory()->create([
            'workspace_id' => 1,
            'usermail' => null,
            'app_specific_pass' => null,
        ]);

        AppleSetting::factory()->create([
            'workspace_id' => 2,
        ]);

        AppleSetting::factory()->create([
            'workspace_id' => 3,
        ]);
    }
}
