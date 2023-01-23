<?php

return [
    // auth
    'host' => env('JENKINS_HOST', 'https://jenkins.talusstudio.eu.ngrok.io'),
    'user' => env('JENKINS_USER', ''),
    'token' => env('JENKINS_TOKEN', ''),

    // seeder job name
    'seeder' => 'Seed',
];
