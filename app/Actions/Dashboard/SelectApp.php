<?php

namespace App\Actions\Dashboard;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Contracts\View\View;

use App\Http\Requests\AppInfo\GetAppInfoRequest;

use App\Models\AppInfo;

class SelectApp
{
    use AsAction;

    public function handle(GetAppInfoRequest $request) : View
    {
        $app = AppInfo::find($request->validated('id'));

        return view('appinfo-form')->with('appInfo', $app);
    }
}
