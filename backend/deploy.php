<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'alomgyar-backend');

// Project repository
//set('repository', 'git@gitlab.com:weborigo/alomgyar/backend.git');
set('repository', 'git@gitlab.weborigo.eu:weborigo-projects/lomgy-r/backend.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', ['storage']);

// Writable dirs by web server
add('writable_dirs', ['shared', 'storage', 'vendor']);
set('allow_anonymous_stats', false);

// // BASIC SETUP FOR LIVE ENV
// $data = [
//     'host' => '195.231.34.81',
//     'deploy_path' => '/var/www/alomgyar/{{branch}}/backend',
//     'remote_user' => 'gitlab',
//     'docker_path' => '/var/www/docker',
//     'working_dir_during_deploy' => '/var/www/alomgyar/{{branch}}/backend/release',
//     'working_dir_after_deploy' => '/var/www/alomgyar/{{branch}}/backend/current',
//     'container_name' => 'origoki_workspace_1',
//     'update_code_strategy' => 'clone',
//     'keep_releases' => 5,
//     'http_user' => '1000',
// ];

// if(get('branch') == 'staging')
// {
// $data['host'] = '80.211.121.129';
// $data['deploy_path'] = '/var/www/alomgyar-backend/deploy';
// $data['docker_path'] = '/var/www/alomgyar-backend';
// $data['working_dir_during_deploy'] = '/var/www/alomgyar-backend/deploy/release';
// $data['working_dir_after_deploy'] = '/var/www/alomgyar-backend/deploy/current';
// $data['container_name'] = 'alomgyar_backend_dev_app';
// }

// host($data['host'])
//     ->set('deploy_path', $data['deploy_path'])
//     ->set('remote_user', $data['remote_user'])
//     ->set('docker_path', $data['docker_path'])
//     ->set('working_dir_during_deploy', $data['working_dir_during_deploy'])
//     ->set('working_dir_after_deploy', $data['working_dir_after_deploy'])
//     ->set('container_name', $data['container_name'])
//     ->set('update_code_strategy', $data['update_code_strategy'])
//     ->set('keep_releases', $data['keep_releases'])
//     ->set('http_user', $data['http_user']);

// PROD TEST SETUP
host('pamadmin.hu')
    ->set('hostname', '195.231.36.254')
    ->setLabels([
        'setup' => 'prod',
    ])
    ->set('branch', 'prod')
    ->set('deploy_path', '/var/www/alomgyar-backend/deploy/prod')
    ->set('remote_user', 'gitlab')
    ->set('docker_path', '/var/www/alomgyar-backend')
    ->set('working_dir_during_deploy', '/var/www/alomgyar-backend/deploy/prod/release')
    ->set('working_dir_after_deploy', '/var/www/alomgyar-backend/deploy/prod/current')
    ->set('container_name', 'alomgyar_backend_app')
    ->set('update_code_strategy', 'clone')
    ->set('keep_releases', 5)
    ->set('http_user', '1000');

// DEV SETUP
host('dev.pamadmin.hu')
    ->set('hostname', '80.211.121.129')
    ->setLabels([
        'setup' => 'dev',
    ])
    ->set('branch', 'dev')
    ->set('deploy_path', '/var/www/alomgyar-backend/deploy/{{branch}}')
    ->set('remote_user', 'gitlab')
    ->set('docker_path', '/var/www/alomgyar-backend')
    ->set('working_dir_during_deploy', '/var/www/alomgyar-backend/deploy/{{branch}}/release')
    ->set('working_dir_after_deploy', '/var/www/alomgyar-backend/deploy/{{branch}}/current')
    ->set('container_name', 'alomgyar_backend_dev_app')
    ->set('update_code_strategy', 'clone')
    ->set('keep_releases', 5)
    ->set('http_user', '1000');

// STAGING SETUP
host('staging.pamadmin.hu')
    ->set('hostname', '80.211.121.129')
    ->setLabels([
        'setup' => 'staging',
    ])
    ->set('branch', 'staging')
    ->set('deploy_path', '/var/www/alomgyar-backend-staging/deploy/{{branch}}')
    ->set('remote_user', 'gitlab')
    ->set('docker_path', '/var/www/alomgyar-backend')
    ->set('working_dir_during_deploy', '/var/www/alomgyar-backend-staging/deploy/{{branch}}/release')
    ->set('working_dir_after_deploy', '/var/www/alomgyar-backend-staging/deploy/{{branch}}/current')
    ->set('container_name', 'alomgyar_backend_staging_app')
    ->set('update_code_strategy', 'clone')
    ->set('keep_releases', 5)
    ->set('http_user', '1000');
// Tasks

task('docker:deploy:vendors', function () {
    // Note currently it is not necessary to move dirs,
    // but the style might be something like this
    /// when we make docker-compose work here.
    run('cd {{docker_path}}');
    run('docker exec -u 1000 -w {{working_dir_during_deploy}} {{container_name}} composer install --no-interaction --prefer-dist --optimize-autoloader');
});

task('docker:artisan:migrate', function () {
    run('cd {{docker_path}}');
    run('docker exec -u 1000 -w {{working_dir_during_deploy}} {{container_name}} php artisan migrate --force');
    // run('docker exec -w {{working_dir_during_deploy}} {{container_name}} php artisan db:seed');
});

task('deploy', [
    // Clone the code
    'deploy:info',
    'deploy:setup',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    // Installs deps inside container
    'docker:deploy:vendors',
    // Only then make everything writable
    'deploy:writable',
    // Migrate
    'docker:artisan:migrate',
    // Symlink shared dirs and files, unlock deploy, remove old releases
    'deploy:publish',
]);

task('configure', function () {
    run('cd {{docker_path}}');
    run('docker exec -w {{working_dir_after_deploy}} {{container_name}} php artisan queue:restart');
    run('docker exec -w {{working_dir_after_deploy}} {{container_name}} php artisan storage:link');
    run('docker exec -w {{working_dir_after_deploy}} {{container_name}} php artisan queue:restart');
    run('docker exec -w {{working_dir_after_deploy}} {{container_name}} chmod -R 777 app/Components/Szamlazz/pdf/');
    run('docker exec -w {{working_dir_after_deploy}} {{container_name}} chmod -R 777 app/Components/Szamlazz/xmls/');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
