<?php

namespace Plugin;

use Model\User;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclAdapter;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;

class Auth extends Plugin
{

    /**
     * @var Acl $acl
     */
    protected $acl;

    /**
     * @param AclAdapter $acl
     */
    public function __construct(AclAdapter $acl)
    {
        $this->acl = $acl;
    }

    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $role = 'guest';
        if ($this->session->has('__access_token')) {
            $token = $this->session->get('__access_token');
            if ($token) {
                /*$this->_google->setAccessToken($token);
                if (!$this->_google->isAccessTokenExpired()) {
                    $role = 'user';
                }*/
            }
        }

        //Tets only
        $role = 'user';
        $user = User::findFirst(1);
        $dispatcher->setParam('user', $user);

        $controller = strtolower($dispatcher->getControllerName());
        $action = strtolower($dispatcher->getActionName());

        if (!$this->acl->isAllowed($role, $controller, $action)) {
            $this->session->set('__callback_url', $this->request->getServer('REQUEST_URI'));
            $dispatcher->forward([
                'controller' => 'oauth',
                'action' => 'index',
            ]);
            return false;
        }
        return true;
    }

}