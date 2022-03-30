<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppInfo>
 */
class AppInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'app_icon' => '',
            'app_name' => $this->faker->name(),
            'app_bundle' => config('appstore.bundle_prefix') . 'Example' . Str::random(5),
            'appstore_id' => Str::random(10),
            'fb_app_id' => $this->faker->numberBetween(100000000, 999999999),
            'elephant_id' => Str::random(10),
            'elephant_secret' => Str::random(10)
        ];
    }
}
