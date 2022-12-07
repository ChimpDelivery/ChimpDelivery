<?php

namespace App\Actions\Dashboard;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;

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
}
