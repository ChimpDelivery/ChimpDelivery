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
        // workspace_id === 1 is default workspace for new users, no invite codes for that workspace.
        return [
            'workspace_id' => rand(2, 3),
            'code' => Str::random(10),
        ];
    }
}
