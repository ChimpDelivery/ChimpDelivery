<?php
namespace Deployer;

// mv ~/Downloads/DashboardSSH.pem ~/.ssh/DashboardSSH.pem
// sudo chmod 600 ~/.ssh/mypemfile.pem (otherwise => WARNING: UNPROTECTED PRIVATE KEY FILE!)
require 'recipe/laravel.php';

// Config

set('application', 'TalusWebBackend');

set('repository', 'https://emrekovanci@github.com/TalusStudio/TalusWebBackend');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts


host('52.30.195.144')
    ->setRemoteUser('ubuntu')
    ->setDeployPath('/var/www/html/TalusWebBackend')
    ->setIdentityFile('~/.ssh/DashboardSSH.pem');

after('deploy:failed', 'deploy:unlock');
