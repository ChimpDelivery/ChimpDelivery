<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\AppStoreConnectSetting;

class AppStoreConnectSettingFactory extends Factory
{
    protected $model = AppStoreConnectSetting::class;

    public function definition()
    {
        return [
            'workspace_id' => 1,
            'private_key' => Str::random(10),
            'issuer_id' => Str::random(10),
            'kid' => Str::random(10),
        ];
    }
}
