<?php
require_once 'config.php';
require_once 'System/Router.php';

$router = new Router();
$router->run();
/*
require_once 'vendor/Framework/Core/Router.php';
require_once 'vendor/Framework/Core/App.php';
require_once 'vendor/Framework/Autoload.php';

$router = new Router();



$router->run();
*/