<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class FtpService
{
    public function __construct(
        public readonly string $domain
    ) { }

    public function GetClient() : FilesystemAdapter
    {
        return Storage::disk('ftp');
    }
}
