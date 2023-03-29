<?php

namespace App\Actions\Api\S3;

use Lorisleiva\Actions\Concerns\AsAction;

use App\Models\AppInfo;
use App\Services\S3Service;

class GetAppIcon
{
    use AsAction;

    public function __construct(
        private readonly S3Service $s3
    ) {
    }

    public function handle(AppInfo $app) : string
    {
        return empty($app->app_icon) || !$this->s3->IsFileExists($app->app_icon)
            ? asset('default-app-icon.png')
            : $this->s3->GetFileLink($app->app_icon);
    }
}
