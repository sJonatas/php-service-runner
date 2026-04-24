<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorTrap;
use Cake\Log\Log;

try {
    Configure::config('default', new \Cake\Core\Configure\Engine\PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    exit($e->getMessage() . "\n");
}

if (file_exists(CONFIG . 'app_local.php')) {
    Configure::load('app_local', 'default');
}

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');

ConnectionManager::setConfig(Configure::consume('Datasources'));
Log::setConfig(Configure::consume('Log'));
Cache::setConfig(Configure::consume('Cache'));

(new ErrorTrap(Configure::read('Error')))->register();
