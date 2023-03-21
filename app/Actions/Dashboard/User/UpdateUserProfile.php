<?php

namespace App\Actions\Dashboard\User;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;

use App\Http\Requests\User\UpdateUserProfileRequest;

class UpdateUserProfile
{
    use AsAction;

    public function handle(UpdateUserProfileRequest $request) : RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->only(['name']))->save();
        return back()->with('success', "User: <b>{$user->name}</b> updated.");
    }
}
