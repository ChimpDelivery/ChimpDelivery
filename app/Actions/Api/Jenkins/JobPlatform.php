<?php

namespace App\Actions\Api\Jenkins;

enum JobPlatform : string
{
    case Appstore = 'Appstore';
    case GooglePlay = 'GooglePlay';
}
