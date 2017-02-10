<?php

namespace App\StorageBundle\Tests\Repository;

use App\TestsBundle\KernelAwareTest;
use App\CoreBundle\Entity\Brand;

class BrandTest extends KernelAwareTest
{
    /**
     * Test basic actions on the Brand repository
     */
    public function testRepoActions()
    {
        $brandRepo = $this->storageManager->getRepo(Brand::class);

        // No existing brand
        $this->assertEquals(0, $brandRepo->countAll());

        // Insert a brand and check ID has been valued
        $firstBrand = new Brand();
        $firstBrand->setName('Motorola');

        $brandRepo->persist($firstBrand);

        $this->assertEquals(1, $brandRepo->countAll());

        $this->assertNotNull($firstBrand->getId());

        $entity = $brandRepo->find($firstBrand->getId());
        $this->assertNotNull($entity);

        // Find all the brands
        $entities = $brandRepo->findAll();
        $this->assertNotEmpty($entities);

        // Insert another brand
        $secondBrand = new Brand();
        $secondBrand->setName('Samsung');

        $brandRepo->persist($secondBrand);

        $this->assertNotNull($secondBrand->getId());

        // IDs are different
        $this->assertNotEquals($firstBrand->getId(), $secondBrand->getId());

        // FindAll() returns 2 entities
        $entities = $brandRepo->findAll();
        $this->assertEquals(2, count($entities));

        // Remove 2nd entity and check it has been removed
        $brandId = $secondBrand->getId();
        $brandRepo->delete($secondBrand);

        $this->assertIsNull($brandRepo->find($brandId));

        $this->assertEquals(1, $brandRepo->countAll());

        // Remove first entity
        $brandRepo->delete($firstBrand);

        $this->assertEmpty($brandRepo->findAll());
        $this->assertEquals(0, $brandRepo->countAll());
    }
}