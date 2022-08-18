<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Package::factory(10)->create();
    }
}
