<?php

namespace Deployer;

require 'recipe/laravel.php';

(new \Dotenv\Dotenv(__DIR__))->load();

// Project name
set('application', env('APP_NAME'));

// Project repository
set('repository', env('DEPLOY_REPOSITORY'));

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', ['storage']);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts

inventory('hosts.yml');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

desc('Creating .env symlink');
task('deploy:env', function () {
    run('cd {{release_path}} && cp .env.deploy .env -f');
});

desc('Generate passport secret');
task('passport:install', function () {
    $output = run('{{bin/php}} {{release_path}}/artisan passport:install');
    writeln('<info>'.$output.'</info>');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

// Deploy .env before cache config.
before('artisan:config:cache', 'deploy:env');
