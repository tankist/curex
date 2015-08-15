<?php

namespace Model;

use Phalcon\Mvc\Model;

/**
 * Class User
 * @package Model
 *
 * @method Offer[] getOffers
 */
class User extends Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $full_name;

    /**
     *
     * @var string
     */
    protected $created;

    /**
     *
     * @var string
     */
    protected $oauth_id;

    /**
     *
     * @var string
     */
    protected $oauth_data;

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
     * Method to set the value of field full_name
     *
     * @param string $full_name
     * @return $this
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;

        return $this;
    }

    /**
     * Method to set the value of field created
     *
     * @param string $created
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Method to set the value of field oauth_id
     *
     * @param string $oauth_id
     * @return $this
     */
    public function setOauthId($oauth_id)
    {
        $this->oauth_id = $oauth_id;

        return $this;
    }

    /**
     * Method to set the value of field oauth_data
     *
     * @param string $oauth_data
     * @return $this
     */
    public function setOauthData($oauth_data)
    {
        $this->oauth_data = $oauth_data;

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
     * Returns the value of field full_name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Returns the value of field created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Returns the value of field oauth_id
     *
     * @return string
     */
    public function getOauthId()
    {
        return $this->oauth_id;
    }

    /**
     * Returns the value of field oauth_data
     *
     * @return string
     */
    public function getOauthData()
    {
        return $this->oauth_data;
    }

    public function initialize()
    {
        $this->setSource('users');

        $this->hasMany('id', 'Model\\Offer', 'user_id', ['alias' => 'Offers']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'full_name' => 'full_name',
            'created' => 'created',
            'oauth_id' => 'oauth_id',
            'oauth_data' => 'oauth_data'
        );
    }

}
