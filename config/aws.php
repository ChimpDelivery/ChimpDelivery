<?php

return [
    's3' => [
        // bucket root
        'base_path' => implode('/', [
            env('AWS_BUCKET_ROOT_FOLDER', 'Laravel'),
            env('APP_ENV', 'local'),
        ]),

        // workspace bucket root
        'ws_path' => implode('/', [
            env('AWS_BUCKET_ROOT_FOLDER', 'Laravel'),
            env('APP_ENV', 'local'),
            'Workspaces',
        ]),

        // indicates custom-header contains filename
        'filename-header-key' => 'Dashboard-File-Name',
    ],
];
