<?php

namespace App\CoreBundle\Entity;

/**
 * Class Entity : Abstract class for entities objects
 *
 * @package App\CoreBundle\Entity
 *
 */
abstract class Entity
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}