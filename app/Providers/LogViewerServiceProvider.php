<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use App\Models\User;

class LogViewerServiceProvider extends ServiceProvider
{
    public function register() : void
    {
    }

    public function boot() : void
    {
        Gate::define('viewLogViewer', function (User $user) {
            return App::isLocal() || $user->isSuperAdmin();
        });
    }
}
