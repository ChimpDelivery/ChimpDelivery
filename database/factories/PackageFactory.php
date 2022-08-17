<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\Package;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition()
    {
        return [
            'url' => 'www.github.com/testuser/'.Str::random(5),
            'package_id' => 'com.' . Str::random(4) . '.' . Str::random(5),
            'hash' => Str::random(16)
        ];
    }
}
