<?php
/**
 * Created by PhpStorm.
 * User: cjohnson
 * Date: 5/11/16
 * Time: 10:34 PM
 */

namespace ChrisJohnson00\ClashOfClansAPIClient\Entity;

use JMS\Serializer\Annotation\Type;

class Locations
{
    /**
     * @Type("array<ChrisJohnson00\ClashOfClansAPIClient\Entity\Location>")
     * @var $items Location[]
     */
    public $items;

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }
}