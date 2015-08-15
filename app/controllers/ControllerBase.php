<?php

namespace Controller;

use Model\User;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    /**
     * @var User
     */
    protected $user;

    public function initialize()
    {
        $user = $this->dispatcher->getParam('user');
        if ($user instanceof User) {
            $this->user = $user;
        }
    }

}
