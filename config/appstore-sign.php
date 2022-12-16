<?php

return [
    'provision' => [
        // required headers for App Store app-signing on Jenkins Sign Stage.
        // tags gonna fetched from .mobileprovision file
        'required_tags' => [

            'name' => [
                'file' => 'Name',
                'web' => 'Dashboard-Provision-Profile-Name',
            ],

            'uuid' => [
                'file' => 'UUID',
                'web' => 'Dashboard-Provision-Profile-UUID',
            ],

            'team-id' => [
                'file' => 'TeamIdentifier',
                'web' => 'Dashboard-Team-ID',
            ],

            'expire' => [
                'file' => 'ExpirationDate',
                'web' => 'Dashboard-Provision-Profile-Expire',
            ],
        ],

        // .mobileprovision mime-type
        'mime' => 'application/octet-stream',

        // check GetFileTags() in "GetProvisionProfile.php"
        'data-index' => 3,
    ],

    'certificate' => [
        // .p12 mime-type
        'mime' => 'application/x-pkcs12',
    ],
];
