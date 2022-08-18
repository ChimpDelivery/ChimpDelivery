<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\Workspace;

class WorkspaceFactory extends Factory
{
    protected $model = Workspace::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'appstore_private_key' => Str::random(10),
            'appstore_issuer_id' => Str::random(10),
            'appstore_kid' => Str::random(10),
            'apple_usermail' => $this->faker->unique()->safeEmail(),
            'apple_app_pass' => Str::random(10),
            'github_org_name' => Str::random(10),
            'github_access_token' => Str::random(10),
            'github_template' => 'Unity3D-Template',
            'github_topic' => 'prototype',
            'api_key' => Str::random(10),
        ];
    }
}
