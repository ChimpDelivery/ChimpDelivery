<?php

/*
 * LaraLens Configuration.
 */
return [
    'prefix' => env('LARALENS_PREFIX', 'laralens'), // URL prefix (default=laralens)
    'middleware' => ['web', 'auth', 'verified', 'can:viewLaraLens'], // middleware (default=web) more separate with ;
    'web-enabled' => env('LARALENS_WEB_ENABLED', 'on'), // Activate web view (default=off)
];
