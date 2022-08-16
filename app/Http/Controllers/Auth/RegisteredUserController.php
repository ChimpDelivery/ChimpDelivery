<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkspaceInviteCode;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', 'captcha'],
            'invite_code' => [
                'required',
                'string',
                Rule::exists('workspace_invite_codes', 'code')->whereNull('deleted_at')
            ]
        ]);

        // find invite code
        $inviteCode = WorkspaceInviteCode::where('code', '=', $request->invite_code)->firstOrFail();

        $user = User::create([
            'workspace_id' => $inviteCode->workspace_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(60)
        ]);

        event(new Registered($user));

        Auth::login($user);

        // expires invite code
        $inviteCode->delete();

        return redirect(RouteServiceProvider::HOME);
    }
}
