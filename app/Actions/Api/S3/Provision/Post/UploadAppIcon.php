<?php

namespace App\Actions\Api\S3\Provision\Post;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use App\Events\AppChanged;
use App\Traits\AsS3Client;
use App\Traits\AsActionResponse;

class UploadAppIcon
{
    use AsAction;
    use AsActionResponse;
    use AsS3Client;

    public function handle(AppChanged $event) : void
    {
        if ($event->request->hasFile('app_icon'))
        {
            $uploadedIcon = $this->UploadToS3($event->request->validated('app_icon'));
            if ($uploadedIcon)
            {
                Cache::forget($event->appInfo->app_icon);

                $event->appInfo->fill([ 'app_icon' => $uploadedIcon ]);
                $event->appInfo->save();
            }
        }
    }

    public function authorize() : bool
    {
        return Auth::user()->can('create-app');
    }
}
