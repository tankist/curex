<?php

namespace Controller;

use Controller\Exception\AccessDeniedException;
use Controller\Exception\NotFoundException;
use Model\Offer;

/**
 * Class OffersController
 * @package Controller
 */
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
        $id = $this->request->get('id', 'int');
        $offer = Offer::findFirst($id);
        if (!$offer instanceof Offer) {
            throw new NotFoundException('Offer not found');
        }
        if ($offer->getUserId() !== $this->user->getId()) {
            throw new AccessDeniedException('This is not your offer');
        }
        $this->view->setVar('offer', $offer);
    }

    public function saveAction()
    {
        $id = $this->request->getPost('id', 'int');
        $offer = Offer::findFirst($id);
        if (!$offer instanceof Offer) {
            //New offer
            $offer = new Offer();
            $offer->setStartDate(new \DateTime());
            $offer->setUserId($this->user->getId());
        } else {
            if ($offer->getUserId() !== $this->user->getId()) {
                throw new AccessDeniedException('This is not your offer');
            }
        }

        $form = new \Form\Offer();
        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $offer);
            if ($form->isValid()) {
                $offer->setUpdateDate(new \DateTime());
                $offer->save();
                $this->flashSession->success('Your offer was added successfully');
            } else {
                foreach ($form->getMessages() as $message) {
                    $this->flashSession->error($message);
                }
            }
        }
        $this->response->redirect('/', true);
    }

    public function deleteAction()
    {
        $id = $this->request->get('id', 'int');
        $offer = Offer::findFirst($id);
        if (!$offer instanceof Offer) {
            throw new NotFoundException('Offer not found');
        }
        if ($offer->getUserId() !== $this->user->getId()) {
            throw new AccessDeniedException('This is not your offer');
        }
        $offer->delete();
        $this->response->redirect('/', true);
    }

}

