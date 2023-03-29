<?php

return [
    // auth
    'host' => env('JENKINS_HOST', 'https://dashboard.talusstudio.com'),
    'user' => env('JENKINS_USER', ''),
    'token' => env('JENKINS_TOKEN', ''),

    // seeder job name in Jenkins
    'seeder' => 'Seed',

    // request settings
    'timeout' => 20,
    'connect_timeout' => 8,
];
