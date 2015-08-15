<?php
namespace Plugin;

use Library\Ras\Loggable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class Exception extends Plugin
{

    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $e)
    {
        $this->response->setStatusCode(404, 'PP Not Found');
        $dispatcher->forward([
            'namespace' => 'Controller',
            'controller' => 'error',
            'action' => 'index',
            'params' => [
                0 => $e->getMessage(),
            ],
        ]);
        return false;
    }

}