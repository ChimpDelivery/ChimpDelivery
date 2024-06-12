<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\UnitySetting;

class UnitySettingFactory extends Factory
{
    protected $model = UnitySetting::class;

    public function definition() : array
    {
        return [
            'workspace_id' => 1,
            'serial' => null,
            'username' => null,
            'password' => null,
        ];
    }
}
