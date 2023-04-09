<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\GooglePlaySetting;

class GooglePlaySettingSeeder extends Seeder
{
    public function run() : void
    {
        foreach (range(1, 5) as $id)
        {
            GooglePlaySetting::factory()->create([
                'workspace_id' => $id,
            ]);
        }
    }
}
