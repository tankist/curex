<?php

namespace Form;

use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Form;
use Model\Offer as OfferModel;

/**
 * Class Offer
 * @package Form
 */
class Offer extends Form
{

    public function initialize()
    {
        $id = new Hidden('id');
        $this->add($id);

        $offerType = new Select('offer_type', [
            OfferModel::TYPE_BUY => 'Buy',
            OfferModel::TYPE_SELL => 'Sell',
        ]);
        $this->add($offerType);

        $currency = new Select('currency', [
            'USD' => 'USD',
            'EUR' => 'EUR',
        ]);
        $this->add($currency);

        $amount = new Numeric('amount');
        $this->add($amount);

        $endDate = new Date('end_date');
        $this->add($endDate);

        $rate = new Numeric('rate');
        $this->add($rate);
    }
}