<?php

namespace Form;

use Model\Currency;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Form;
use Model\Offer as OfferModel;
use Phalcon\Validation\Validator\PresenceOf;

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

        $currency = new Select('currency_id', $this->getCurrenciesArray());
        $currency->setLabel('Currency');
        $this->add($currency);

        $amount = new Numeric('amount');
        $this->add($amount);

        $rate = new Numeric('rate');
        $this->add($rate);

        $amount->addValidator(new PresenceOf());
        $rate->addValidator(new PresenceOf());

        return $this;
    }

    /**
     * @return array
     */
    protected function getCurrenciesArray()
    {
        $currencies = [];
        /** @var Currency[] $currenciesRecordSet */
        $currenciesRecordSet = Currency::find();
        foreach ($currenciesRecordSet as $currency) {
            $currencies[$currency->getId()] = $currency->getTitle();
        }
        return $currencies;
    }
}