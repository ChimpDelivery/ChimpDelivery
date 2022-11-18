<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Package;

class PackageSeeder extends Seeder
{
    private $packages = [
        [
            'https://github.com/TalusStudio/TalusFramework-Package',
            'com.talus.talusframework',
            'hash-1',
        ],
        [
            'https://github.com/TalusStudio/TalusKit-Package',
            'com.talus.taluskit',
            'hash-2',
        ],
        [
            'https://github.com/TalusStudio/TalusBackendData-Package',
            'com.talus.talusbackenddata',
            'hash-3',
        ],
        [
            'https://github.com/TalusStudio/TalusCI-Package',
            'com.talus.talusci',
            'hash-4',
        ],
        [
            'https://github.com/TalusStudio/TalusSettings-Package',
            'com.talus.talussettings',
            'hash-5',
        ],
    ];

    public function run()
    {
        foreach ($this->packages as $package)
        {
            Package::factory()->create([
                'url' => $package[0],
                'package_id' => $package[1],
                'hash' => $package[2],
            ]);
        }
    }
}
