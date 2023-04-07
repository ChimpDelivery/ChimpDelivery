<?php

namespace Deployer;

desc('Upload environment file from local.');
task('dashboard:upload:secrets', function() {
    upload('.env', '{{deploy_path}}/shared');
});
