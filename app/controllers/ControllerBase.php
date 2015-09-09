<?php

namespace Controller;

use Model\User;
use Phalcon\Forms\Manager as FormsManager;
use Phalcon\Mvc\Controller;

/**
 * Class ControllerBase
 * @package Controller
 *
 * @property FormsManager $forms
 */
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
