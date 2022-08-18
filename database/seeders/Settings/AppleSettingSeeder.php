<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\AppleSetting;

class AppleSettingSeeder extends Seeder
{
    public function run()
    {
        AppleSetting::factory()->create([
            'workspace_id' => 1,
        ]);

        AppleSetting::factory()->create([
            'workspace_id' => 2,
        ]);

        AppleSetting::factory()->create([
            'workspace_id' => 3,
        ]);
    }
}
