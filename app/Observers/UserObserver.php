<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        $user->update([
            'api_token' => Str::random(60)
        ]);
    }

    public function updated(User $user)
    { }

    public function deleted(User $user)
    { }

    public function restored(User $user)
    { }

    public function forceDeleted(User $user)
    { }
}
