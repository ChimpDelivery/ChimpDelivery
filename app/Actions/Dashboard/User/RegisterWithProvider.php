<?php

namespace App\Actions\Dashboard\User;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

use Laravel\Socialite\Facades\Socialite;

use App\Models\User;
use App\Providers\RouteServiceProvider;

class RegisterWithProvider
{
    use AsAction;

    private const PROVIDER = 'github';

    public function handle() : RedirectResponse
    {
        try
        {
            $githubUser = Socialite::driver(self::PROVIDER)->user();
        }
        catch (\Exception $exception)
        {
            return redirect('login')->intended()->withErrors($exception->getMessage());
        }

        $user = User::whereEmail($githubUser->email)->first();
        if ($user)
        {
            Auth::login($user);
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $newUser = User::updateOrCreate([ 'email' => $githubUser->email ], [
            'workspace_id' => config('workspaces.default_ws_id'),
            'name' => $githubUser->getName() ?? 'No Name',
            'password' => Hash::make(Str::random(8))
        ])->syncRoles([ 'User' ]);

        $newUser->markEmailAsVerified();

        event(new Registered($newUser));

        Auth::login($newUser);
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function authorize() : bool
    {
        return Auth::guest();
    }
}
