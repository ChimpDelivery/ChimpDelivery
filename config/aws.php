<?php

return [
    's3' => [
        // bucket root
        'base_path' => implode('/', [
            'TalusDashboard_Root',
            env('APP_ENV', 'local'),
        ]),

        // workspace bucket root
        'ws_path' => implode('/', [
            'TalusDashboard_Root',
            env('APP_ENV', 'local'),
            'Workspaces'
        ]),

        // indicates custom-header contains filename
        'filename-header-key' => 'Dashboard-File-Name',
    ],
];
