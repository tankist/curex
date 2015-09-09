<?php

namespace Controller;

use Controller\Exception\AccessDeniedException;
use Controller\Exception\NotFoundException;
use Model\Offer;
use Phalcon\Validation\Message;

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
        /** @var \Form\Offer $form */
        $form = $this->forms->get('offer');
        if ($data = $this->session->get('saveOfferData')) {
            $form->bind(json_decode($data, true), new Offer());
            $this->session->remove('saveOfferData');
        }
        $this->view->setVar('form', $form);
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
        $fallbackParams = [
            'for' => 'editOffer',
            'id' => $id,
        ];
        if (!$offer instanceof Offer) {
            //New offer
            $offer = new Offer();
            $offer->setUserId($this->user->getId());
            $fallbackParams['for'] = 'newOffer';
            unset($fallbackParams['id']);
        } else {
            if ($offer->getUserId() !== $this->user->getId()) {
                throw new AccessDeniedException('This is not your offer');
            }
        }

        $form = $this->forms->get('offer');
        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $offer);
            if ($form->isValid()) {
                $result = $offer->save();
                if ($result) {
                    $this->flashSession->success('Your offer was added successfully');
                } else {
                    /** @var Message $message */
                    foreach ($form->getMessages() as $message) {
                        $this->flashSession->error($message->getMessage());
                    }
                }
            } else {
                /** @var Message $message */
                foreach ($form->getMessages() as $message) {
                    $this->flashSession->error($message->getMessage());
                }
                $editOfferData = [];
                foreach ($form->getElements() as $element) {
                    $editOfferData[$element->getName()] = $element->getValue();
                }
                $this->session->set('saveOfferData', json_encode($this->request->getPost()));
                $this->response->redirect($this->url->get($fallbackParams), true);
                return;
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

