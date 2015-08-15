<?php

namespace Controller;

/**
 * Class IndexController
 * @package Controller
 */
class IndexController extends ControllerBase
{

    public function indexAction()
    {
        $this->dispatcher->forward([
            'controller' => 'offers',
        ]);
    }

}

