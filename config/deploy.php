<?php

return [
    // must be synced with secret name on GitHub repository environments
    'dotenv_secret_name' => env('GITHUB_DOTENV_SECRET_NAME', 'DOTENV'),

    // TalusWebBackend repository id on GitHub
    'repository_id' => env('GITHUB_REPOSITORY_ID'),
];
