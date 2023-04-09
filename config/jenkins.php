<?php

return [
    // auth
    'host' => env('JENKINS_HOST', ''),
    'user' => env('JENKINS_USER', ''),
    'token' => env('JENKINS_TOKEN', ''),

    // seeder job name in Jenkins
    'seeder' => 'Seed',

    // request settings
    'timeout' => 15,
    'connect_timeout' => 6,
];
