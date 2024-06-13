<?php

namespace App\Actions\Api\Jenkins;

enum JobPlatform : string
{
    case Appstore = 'Appstore';
    case GooglePlay = 'GooglePlay';

    public function GetPlatformIcon() : string
    {
        return match ($this)
        {
            self::Appstore => config('icons.apple'),
            self::GooglePlay => config('icons.google'),
            default => 'fa fa-question',
        };
    }

    // current supported platforms in dashboard
    public static function GetActivePlatforms() : array
    {
        return [
            self::Appstore->value,
            self::GooglePlay->value,
        ];
    }
}
