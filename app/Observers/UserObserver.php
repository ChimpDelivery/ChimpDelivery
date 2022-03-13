<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {        
        $user->api_token = Str::random(60);
        $user->save();
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
