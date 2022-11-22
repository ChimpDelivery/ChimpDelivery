<?php

namespace App\Actions\Dashboard\AppStoreConnect;

use Illuminate\Contracts\View\View;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateBundleIdForm
{
    use AsAction;

    public function handle() : View
    {
        return view('create-bundle-form');
    }
}
