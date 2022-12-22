<?php

namespace App\Services;

class FtpService
{
    public function __construct(private readonly string $ftpDomain)
    { }

    public function GetDomain() : string
    {
        return $this->ftpDomain;
    }
}
