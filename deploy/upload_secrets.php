<?php

namespace Deployer;

desc('Upload environment file from local.');
task('dashboard:upload:secrets', function() {
    echo runLocally('pwd');
    echo runLocally('test -f .env && echo env exists!');

    $dotenv = runLocally('cat .env');
    upload($dotenv, '{{deploy_path}}/shared/.env');
});
