<?php

return [

    // all permissions in app
    'permissions' => [

        // newly registered user related
        'create workspace',
        'join workspace',

        // workspace admins related
        'view workspace',
        'update workspace',
        'delete app',
        'scan jobs',

        // workspace users related
        'create app',
        'view apps',
        'update app',
        'create bundle',
        'build job',
        'abort job',
        'view job log',
        'create api token',

        // note: no use-case currently
        'delete workspace',
    ],

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
            'build job',
            'abort job',
            'view job log',
            'create api token',
        ],

        // all permissions for workspace admins
        'Admin_Workspace' => [
            'view workspace',
            'update workspace',
            'delete app',
            'scan jobs',
            'view apps',
            'create app',
            'update app',
            'create bundle',
            'build job',
            'abort job',
            'view job log',
            'create api token',
        ],

        'Admin_Super' => [

        ],
    ]
];
