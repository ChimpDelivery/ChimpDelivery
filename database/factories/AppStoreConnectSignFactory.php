<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\AppStoreConnectSign;

class AppStoreConnectSignFactory extends Factory
{
    protected $model = AppStoreConnectSign::class;

    public function definition()
    {
        return [
            'workspace_id' => 1,
            'cert' => null,
            'provision_profile' => null,
        ];
    }
}
