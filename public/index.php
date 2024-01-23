<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Forge\core\Application as CoreApplication;


$app = new CoreApplication();

$app->loadWebRoutes();

$app->run();
