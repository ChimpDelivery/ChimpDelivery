<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\UnitySetting;

class UnitySettingSeeder extends Seeder
{
    public function run() : void
    {
        foreach (range(1, 5) as $id)
        {
            UnitySetting::factory()->create([
                'workspace_id' => $id,
            ]);
        }
    }
}
