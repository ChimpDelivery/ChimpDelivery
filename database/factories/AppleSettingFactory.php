<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\AppleSetting;

class AppleSettingFactory extends Factory
{
    protected $model = AppleSetting::class;

    public function definition()
    {
        return [
            'workspace_id' => 1,
            'usermail' => $this->faker->unique()->safeEmail(),
            'app_specific_pass' => Str::random(10),
        ];
    }
}
