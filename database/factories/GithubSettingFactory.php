<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\GithubSetting;

class GithubSettingFactory extends Factory
{
    protected $model = GithubSetting::class;

    public function definition()
    {
        return [
            'workspace_id' => 1,
            'personal_access_token' => null,
            'organization_name' => Str::random(10),
            'template_name' => null,
            'topic_name' => null,
            'public_repo' => true,
            'private_repo' => false,
        ];
    }
}
