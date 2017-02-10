<?php

namespace App\CoreBundle\EntityFactory;

use App\CoreBundle\Entity\Brand;

/**
 * Concrete EntityFactory implementation for Brand entity
 *
 * @package App\CoreBundle\EntityFactory
 *
 */
class BrandFactory extends EntityFactory
{
    public function __construct()
    {
        $this->className = Brand::class;
    }
}