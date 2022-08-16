<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workspace>
 */
class WorkspaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'appstore_private_key' => '',
            'appstore_issuer_id' => $this->faker->name(),
            'appstore_kid' => $this->faker->name(),
            'github_org_name' => 'TalusStudio',
            'github_access_token' => Str::random(10),
        ];
    }
}
