<?php

namespace App\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity Brand : brands selling phone
 *
 * @package App\CoreBundle\Entity
 *
 */
class Brand extends Entity
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var ArrayCollection
     */
    protected $phones;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection Collection of Phones
     */
    public function getPhones()
    {
        if (!isset($this->phones)) {
            return new ArrayCollection();
        }

        return $this->phones;
    }

    /**
     * @return bool Whether phones property has been initialized
     */
    public function isPhonesInitialized()
    {
        return isset($this->phones);
    }

    /**
     * @param ArrayCollection $phones
     */
    public function setPhones($phones)
    {
        if (is_array($phones)) {
            $phones = new ArrayCollection($phones);
        }
        $this->phones = $phones;
    }
}