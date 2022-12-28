<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInviteCode;

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
            'name' => [ 'required', 'string', 'max:255' ],
            'email' => [ 'required', 'string', 'email', 'max:255', 'unique:users' ],
            'password' => [ 'required', 'confirmed', Rules\Password::defaults() ],
            'invite_code' => [ 'nullable', 'alpha_num' ],
            recaptchaFieldName() => recaptchaRuleName()
        ]);

        // find invite code
        $inviteCode = WorkspaceInviteCode::whereBlind('code', 'code', $request->invite_code)->first();

        // workspace 1 default workspace for new users
        $user = User::create([
            'workspace_id' => ($inviteCode)
                ? $inviteCode->workspace_id
                : config('workspaces.DEFAULT_WS_ID'),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->syncRoles([ ($inviteCode) ? 'User_Workspace' : 'User' ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
