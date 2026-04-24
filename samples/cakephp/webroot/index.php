<?php

require dirname(__DIR__) . '/config/paths.php';

require ROOT . DS . 'config' . DS . 'bootstrap.php';

use App\Application;
use Cake\Http\Server;

$server = new Server(new Application(CONFIG));
$server->emit($server->run());
