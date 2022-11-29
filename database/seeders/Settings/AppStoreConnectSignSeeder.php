<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;

use App\Models\AppStoreConnectSign;

class AppStoreConnectSignSeeder extends Seeder
{
    public function run()
    {
        foreach (range(1, 5) as $id)
        {
            AppStoreConnectSign::factory()->create([
                'workspace_id' => $id,
            ]);
        }
    }
}
