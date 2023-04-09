<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\GooglePlaySetting;

class GooglePlaySettingFactory extends Factory
{
    protected $model = GooglePlaySetting::class;

    public function definition() : array
    {
        return [
            'workspace_id' => 1,
            'keystore_file' => null,
            'keystore_pass' => null,
            'service_account' => null,
        ];
    }
}
