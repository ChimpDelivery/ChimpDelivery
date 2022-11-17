<?php

namespace App\Actions\Dashboard\User;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\User\UpdateUserProfileRequest;

class UpdateProfile
{
    use AsAction;

    public function handle(UpdateUserProfileRequest $request) : RedirectResponse
    {
        Auth::user()->fill($request->safe()->only(['name']))->save();
        return back()->with('success', 'User Profile updated.');
    }
}
