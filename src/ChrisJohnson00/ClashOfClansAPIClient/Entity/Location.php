<?php

/**
 * Created by PhpStorm.
 * User: cjohnson
 * Date: 5/11/16
 * Time: 10:30 PM
 */

namespace ChrisJohnson00\ClashOfClansAPIClient\Entity;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

class Location
{
    /**
     * @Type("integer")
     */
    public $id;
    /**
     * @Type("string")
     */
    public $name;
    /**
     * @SerializedName("isCountry")
     * @Type("boolean")
     */
    public $isCountry;
    /**
     * @SerializedName("countryCode")
     * @Type("string")
     */
    public $countryCode;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getIsCountry()
    {
        return $this->isCountry;
    }

    /**
     * @param mixed $isCountry
     */
    public function setIsCountry($isCountry)
    {
        $this->isCountry = $isCountry;
    }

    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param mixed $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

}