<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Str;

use App\Models\GooglePlaySetting;

class GooglePlaySettingFactory extends Factory
{
    protected $model = GooglePlaySetting::class;

    public function definition()
    {
        return [
            'workspace_id' => 1,
            'keystore_path' => null,
            'keystore_pass' => null,
            'service_account' => null,
        ];
    }
}
