<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword;

class ResetPasswordQueued extends ResetPassword implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;
}
