<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailQueued extends VerifyEmail implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;
}
