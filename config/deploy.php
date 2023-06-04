<?php

return [
    // must be synced with secret name on GitHub repository environments
    'dotenv_secret_name' => env('GITHUB_DOTENV_SECRET_NAME', 'DOTENV'),

    'repository_owner' => env('GITHUB_REPOSITORY_OWNER'),

    // TalusWebBackend repository name on GitHub
    'repository_name' => env('GITHUB_REPOSITORY_NAME'),

    // TalusWebBackend repository id on GitHub
    'repository_id' => env('GITHUB_REPOSITORY_ID'),
];
