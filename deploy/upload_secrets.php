<?php

namespace Deployer;

desc('Upload environment file from local.');
task('dashboard:upload:secrets', function() {
    upload(file_get_contents('.env'), '{{deploy_path}}/shared/.env');
});
