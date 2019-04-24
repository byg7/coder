<?php
require_once 'sys/app/router.php';

$router = new Router();
$router->run(__DIR__.'/src/');
