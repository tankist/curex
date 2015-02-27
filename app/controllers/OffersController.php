<?php

namespace Controller;

use Model\Offer;

class OffersController extends ControllerBase
{

    public function indexAction()
    {
        $offers = Offer::find([
            'order' => 'start_date DESC',
        ]);
        $this->view->setVar('offers', $offers);
    }

    public function newAction()
    {

    }

    public function editAction()
    {

    }

    public function saveAction()
    {

    }

    public function deleteAction()
    {

    }

}

