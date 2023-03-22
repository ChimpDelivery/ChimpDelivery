<?php

namespace App\Actions\Api\S3;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Services\S3Service;

class GetAppIcon
{
    use AsAction;

    public function handle(AppInfo $app) : string
    {
        $s3 = App::makeWith(S3Service::class, [ 'workspace' => Auth::user()->workspace ]);

        return empty($app->app_icon) || !$s3->IsFileExists($app->app_icon)
            ? asset('default-app-icon.png')
            : $s3->GetFileLink($app->app_icon);
    }
}
