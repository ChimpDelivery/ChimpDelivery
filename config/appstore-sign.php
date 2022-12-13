<?php

return [
    'provision' => [

        // required headers for App Store app-signing on Jenkins Sign Stage.
        // tags gonna fetched from .mobileprovision file
        'required_tags' => [
            [
                'file' => 'Name',
                'web' => 'Dashboard-Provision-Profile-Name',
            ],
            [
                'file' => 'UUID',
                'web' => 'Dashboard-Provision-Profile-UUID',
            ],
            [
                'file' => 'TeamIdentifier',
                'web' => 'Dashboard-Team-ID',
            ],
            [
                'file' => 'ExpirationDate',
                'web' => 'Dashboard-Provision-Profile-Expire',
            ],
        ],

        // .mobileprovision mime-type
        'mime' => 'application/octet-stream',

        // check GetFileTags() in "GetProvisionProfile.php"
        'data-index' => 3,
    ],
];
