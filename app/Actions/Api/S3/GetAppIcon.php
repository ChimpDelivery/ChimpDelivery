<?php

namespace App\Actions\Api\S3;

use Symfony\Component\HttpFoundation\Response;

use App\Models\AppInfo;
use App\Services\S3Service;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAppIcon
{
    use AsAction;

    public function handle(AppInfo $app)
    {
        return empty($app->app_icon)
            ? asset('Talus_icon.ico')
            : app(S3Service::class)->GetFileLink($app->app_icon);
    }

    public function authorize() : bool
    {
        return !Auth::user()->isNew();
    }
}
