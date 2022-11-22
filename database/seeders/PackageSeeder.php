<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Package;

class PackageSeeder extends Seeder
{
    private $packages = [
        [
            'https://github.com/TalusStudio/TalusFramework-Package.git',
            'com.talus.talusframework',
            'hash-1',
        ],
        [
            'https://github.com/TalusStudio/TalusKit-Package.git',
            'com.talus.taluskit',
            'hash-2',
        ],
        [
            'https://github.com/TalusStudio/TalusBackendData-Package.git',
            'com.talus.talusbackenddata',
            'hash-3',
        ],
        [
            'https://github.com/TalusStudio/TalusCI-Package.git',
            'com.talus.talusci',
            'hash-4',
        ],
        [
            'https://github.com/TalusStudio/TalusSettings-Package.git',
            'com.talus.talussettings',
            'hash-5',
        ],
        [
            'https://github.com/TalusStudio-Packages/TalusLevelManagement-Package.git',
            'com.talus.taluslevelmanagement',
            'hash-6',
        ],
        [
            'https://github.com/TalusStudio-Packages/TalusGameSystems-Package.git',
            'com.talus.talusgamesystems',
            'hash-7',
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
