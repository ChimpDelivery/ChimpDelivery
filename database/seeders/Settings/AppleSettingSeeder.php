<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\AppleSetting;

class AppleSettingSeeder extends Seeder
{
    public function run()
    {
        foreach (range(1, 5) as $id)
        {
            AppleSetting::factory()->create([
                'workspace_id' => $id,
            ]);
        }
    }
}
