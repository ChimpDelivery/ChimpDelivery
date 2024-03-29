<?php

return [
    // new users associated with that workspace
    'default_ws_id' => 1,
    'default_org_name' => 'Default Organization',

    // internal workspace
    'internal_ws_id' => 2,

    //
    'superadmin_email' => env('SUPERADMIN_EMAIL'),

    // jenkins token name (internal token used by dashboard, no user-access)
    'jenkins_token_name' => 'jenkins-key',
];
