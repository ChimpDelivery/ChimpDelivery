<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run()
    {
        Package::factory()->create([
            'url' => 'https://github.com/TalusStudio/TalusFramework-Package',
            'package_id' => 'com.talus.talusframework',
            'hash' => '1',
        ]);

        Package::factory()->create([
            'url' => 'https://github.com/TalusStudio/TalusKit-Package',
            'package_id' => 'com.talus.taluskit',
            'hash' => '2',
        ]);

        Package::factory()->create([
            'url' => 'https://github.com/TalusStudio/TalusBackendData-Package',
            'package_id' => 'com.talus.talusbackenddata',
            'hash' => '3',
        ]);

        Package::factory()->create([
            'url' => 'https://github.com/TalusStudio/TalusCI-Package',
            'package_id' => 'com.talus.talusci',
            'hash' => '4',
        ]);

        Package::factory()->create([
            'url' => 'https://github.com/TalusStudio/TalusSettings-Package',
            'package_id' => 'com.talus.talussettings',
            'hash' => '4',
        ]);

        Package::factory(10)->create();
    }
}
