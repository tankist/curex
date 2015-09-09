<?php

namespace Model;

use Phalcon\Mvc\Model;

/**
 * Class Offer
 * @package Model
 *
 * @method User getUser
 * @method Currency getCurrency
 */
class Offer extends Model
{

    const TYPE_BUY = 0;

    const TYPE_SELL = 1;

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $offer_type;

    /**
     *
     * @var string
     */
    protected $currency_id;

    /**
     *
     * @var double
     */
    protected $amount;

    /**
     *
     * @var string
     */
    protected $start_date;

    /**
     *
     * @var string
     */
    protected $end_date;

    /**
     *
     * @var string
     */
    protected $update_date;

    /**
     *
     * @var integer
     */
    protected $user_id;

    /**
     *
     * @var double
     */
    protected $rate;

    /**
     * @var array
     */
    protected static $offerTypes = [self::TYPE_BUY => 'Buy', self::TYPE_SELL => 'Sell'];

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field offer_type
     *
     * @param string $offerType
     * @return $this
     */
    public function setOfferType($offerType)
    {
        if (!in_array($offerType, self::$offerTypes)) {
            throw new \InvalidArgumentException(sprintf('Offer type "%s" not available', $offerType));
        }

        $this->offer_type = array_search($offerType, self::$offerTypes);

        return $this;
    }

    /**
     * Method to set the value of field currency_id
     *
     * @param string $currencyId
     * @return $this
     */
    public function setCurrencyId($currencyId)
    {
        $this->currency_id = $currencyId;

        return $this;
    }

    /**
     * Method to set the value of field amount
     *
     * @param double $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Method to set the value of field start_date
     *
     * @param string $startDate
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;

        return $this;
    }

    /**
     * Method to set the value of field end_date
     *
     * @param string $endDate
     * @return $this
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;

        return $this;
    }

    /**
     * Method to set the value of field update_date
     *
     * @param string $updateDate
     * @return $this
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Method to set the value of field user_id
     *
     * @param integer $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Method to set the value of field rate
     *
     * @param double $rate
     * @return $this
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field offer_type
     *
     * @return string
     */
    public function getOfferType()
    {
        return (isset(self::$offerTypes[$this->offer_type])) ? self::$offerTypes[$this->offer_type] : '';
    }

    /**
     * Returns the value of field currency_id
     *
     * @return string
     */
    public function getCurrencyId()
    {
        return $this->currency_id;
    }

    /**
     * Returns the value of field amount
     *
     * @return double
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Returns the value of field start_date
     *
     * @return string
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Returns the value of field end_date
     *
     * @return string
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Returns the value of field update_date
     *
     * @return string
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field rate
     *
     * @return double
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('offers');

        $this->belongsTo('user_id', 'Model\\User', 'id', ['alias' => 'User']);
        $this->belongsTo('currency_id', 'Model\\Currency', 'id', ['alias' => 'Currency']);

        $this->addBehavior(new Model\Behavior\Timestampable([
            'beforeCreate' => [
                'field' => 'start_date',
                'format' => 'Y-m-d H:i:s',
            ],
            'beforeUpdate' => [
                'field' => 'update_date',
                'format' => 'Y-m-d H:i:s',
            ]
        ]));
    }

    public function beforeSave()
    {
        foreach (['start_date', 'end_date', 'update_date'] as $field) {
            if ($this->$field instanceof \DateTime) {
                $this->$field = $this->$field->format('Y-m-d H:i:s');
            }
        }
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'offer_type' => 'offer_type',
            'currency_id' => 'currency_id',
            'amount' => 'amount',
            'start_date' => 'start_date',
            'end_date' => 'end_date',
            'update_date' => 'update_date',
            'user_id' => 'user_id',
            'rate' => 'rate',
        ];
    }

}
