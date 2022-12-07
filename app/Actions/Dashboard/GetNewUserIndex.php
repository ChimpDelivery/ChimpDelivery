<?php

namespace App\Actions\Dashboard;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class GetNewUserIndex
{
    use AsAction;

    public function handle() : View
    {
        return view('workspace-settings')->with([
            'isNew' => true,
            'cert_label' => 'Choose...',
            'provision_label' => 'Choose...',
        ]);
    }

    public function authorize() : bool
    {
        return Auth::user()->isNew();
    }
}
