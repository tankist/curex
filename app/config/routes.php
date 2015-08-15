<?php

use Phalcon\Mvc\Router;

$router = new Router();

$router->setDefaults([
    'namespace' => 'Controller',
    'controller' => 'index',
    'action' => 'index',
]);

$router->add('/:id/edit', [
    'controller' => 'offers',
    'action' => 'edit',
    'id' => 1,
])->setName('editOffer');

$router->add('/:id/delete', [
    'controller' => 'offers',
    'action' => 'delete',
    'id' => 1,
])->setName('deleteOffer');

$router->add('/save', [
    'controller' => 'offers',
    'action' => 'save',
])->setName('saveOffer')->via('POST');

$router->add('/new', [
    'controller' => 'offers',
    'action' => 'new',
])->setName('newOffer');

return $router;