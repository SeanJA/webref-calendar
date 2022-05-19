<?php

use Dotenv\Dotenv;

date_default_timezone_set('America/Toronto');

require 'vendor/autoload.php';

if (!defined('ENV_LOADED')) {
    $rootDir = __DIR__ . '/..';
    if (file_exists($rootDir . '/.env')) {
        $dotEnv = new Dotenv($rootDir);
        try {
            $dotEnv->load();
        } catch (Exception $e) {

        }
    }
    define('ENV_LOADED', true);
}
