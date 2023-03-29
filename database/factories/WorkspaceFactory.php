<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Workspace;

class WorkspaceFactory extends Factory
{
    protected $model = Workspace::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName . 'Studio',
        ];
    }
}
