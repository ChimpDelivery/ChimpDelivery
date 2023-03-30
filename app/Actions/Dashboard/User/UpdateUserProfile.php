<?php

namespace App\Actions\Dashboard\User;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;

use App\Models\User;
use App\Http\Requests\User\UpdateUserProfileRequest;

class UpdateUserProfile
{
    use AsAction;

    public function handle(User $user, array $inputs) : RedirectResponse
    {
        $user->fill($inputs)->save();
        return back()->with('success', "User: <b>{$user->name}</b> updated.");
    }

    public function asController(UpdateUserProfileRequest $request) : RedirectResponse
    {
        return $this->handle($request->user(), $request->safe()->only([ 'name' ]));
    }
}
