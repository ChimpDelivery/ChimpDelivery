<?php

namespace App\Actions\AppStoreConnect;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;

class CreateBundleIdForm
{
    use AsAction;

    public function handle() : View
    {
        return view('create-bundle-form');
    }
}
