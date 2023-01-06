<?php

// note: sync permission and roles seeders

return [

    // all roles in app with permissions
    'roles' => [

        // newly registered user permissions
        'User' => [
            'create workspace',
            'join workspace',
        ],

        // all permissions for workspace users
        'User_Workspace' => [
            'view apps',
            'create app',
            'update app',
            'create bundle',
            'scan jobs',
            'build job',
            'abort job',
            'view job log',
            'create api token',
        ],

        // workspace admins inherits workspace user permissions
        'Admin_Workspace' => [
            'delete app',
            'view workspace',
            'update workspace',
        ],

        'Admin_Super' => [

        ],
    ]
];
