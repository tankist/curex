<?php

use Phalcon\Mvc\Router;

$router = new Router();

$router->setDefaults([
    'namespace' => 'Controller',
    'controller' => 'index',
    'action' => 'index',
]);

return $router;