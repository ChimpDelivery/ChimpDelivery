<?php

namespace Deployer;

desc('Upload environment file from local.');
task('dashboard:upload:secrets', function() {
    $dotenv = runLocally('cat .env');
    echo $dotenv;
    // upload($dotenv, '{{deploy_path}}/shared/.env');
});
