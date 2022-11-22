<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\AppStoreConnectSetting;

class AppStoreConnectSettingFactory extends Factory
{
    protected $model = AppStoreConnectSetting::class;

    public function definition()
    {
        return [
            'workspace_id' => 1,
            'private_key' => null,
            'issuer_id' => null,
            'kid' => null,
        ];
    }
}
