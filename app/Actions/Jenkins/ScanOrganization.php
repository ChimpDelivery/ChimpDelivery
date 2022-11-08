<?php

namespace App\Actions\Jenkins;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Artisan;

use Illuminate\Http\RedirectResponse;

class ScanOrganization
{
    use AsAction;

    public function ScanRepo() : RedirectResponse
    {
        Artisan::call("jenkins:scan-repo");
        session()->flash('success', "Repository scanning begins...");

        return back();
    }
}
