<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\AppInfo;

class AppInfoFactory extends Factory
{
    protected $model = AppInfo::class;

    public function definition()
    {
        return [
            'workspace_id' => rand(1, 3),
            'app_icon' => '',
            'app_name' => $this->faker->name(),
            'project_name' => $this->faker->name(),
            'app_bundle' => 'com.Example' . Str::random(5),
            'appstore_id' => Str::random(10),
            'fb_app_id' => $this->faker->numberBetween(100000000, 999999999),
            'fb_client_token' => $this->faker->numberBetween(100000000, 999999999),
            'ga_id' => Str::random(10),
            'ga_secret' => Str::random(10),
        ];
    }
}
