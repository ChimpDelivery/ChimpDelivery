<?php

namespace App\Actions\Api\S3;

use App\Models\AppInfo;
use App\Services\S3Service;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAppIcon
{
    use AsAction;

    public function handle(AppInfo $app) : string
    {
        $s3 = app(S3Service::class);

        return empty($app->app_icon) || !$s3->IsFileExists($app->app_icon)
            ? asset('Talus_icon.ico')
            : $s3->GetFileLink($app->app_icon);
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }
}