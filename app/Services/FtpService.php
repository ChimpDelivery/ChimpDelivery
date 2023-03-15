<?php

namespace App\Services;

class FtpService
{
    public function __construct(
        public readonly string $domain
    ) { }
}
