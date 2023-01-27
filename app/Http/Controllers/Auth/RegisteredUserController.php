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
            'password' => [ 'required', Rules\Password::defaults() ],
            'invite_code' => [ 'nullable', 'alpha_num' ],
            recaptchaFieldName() => recaptchaRuleName(),
        ]);

        $defaultWs = config('workspaces.default_ws_id');
        $userWs = $defaultWs;

        if ($request->filled('invite_code'))
        {
            $inviteCode = WorkspaceInviteCode::whereBlind('code', 'code', $request->invite_code)->first();
            if ($inviteCode)
            {
                $userWs = $inviteCode->workspace_id;
            }
        }

        $user = User::create([
            'workspace_id' => $userWs,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->syncRoles([ ($userWs != $defaultWs) ? 'User_Workspace' : 'User' ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
