<?php

namespace App\CoreBundle\Entity;

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

    public function __construct()
    {
    }

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
}