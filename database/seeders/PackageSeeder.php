<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run()
    {
        Package::factory(10)->create();
    }
}
