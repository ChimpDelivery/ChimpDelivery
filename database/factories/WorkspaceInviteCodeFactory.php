<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Models\WorkspaceInviteCode;

class WorkspaceInviteCodeFactory extends Factory
{
    protected $model = WorkspaceInviteCode::class;

    public function definition()
    {
        return [
            'workspace_id' => rand(1, 3),
            'code' => Str::random(10),
        ];
    }
}
