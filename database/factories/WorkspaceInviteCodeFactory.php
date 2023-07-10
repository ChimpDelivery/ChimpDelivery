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
        // workspace_id === 1 is default workspace for new users
        //      no invite codes for that workspace.

        // workspace_id === 2 is internal workspace
        //      no invite codes for that workspace.

        return [
            'workspace_id' => rand(3, 5),
            'code' => str(Str::random(12))->upper()->toString(),
        ];
    }
}
