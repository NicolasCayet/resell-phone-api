<?php

namespace App\TestsBundle;

use Symfony\Component\DependencyInjection\Container;

/**
 * Test case class helpful with Entity tests requiring access to Symfony container, or the database interaction through DataManager
 */
abstract class KernelAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AppKernel
     */
    protected $kernel;
    /**
     * @var Container
     */
    protected $container;
    /**
     * @var \App\StorageBundle\DataManager\DataManager
     */
    protected $dataManager;

    /**
     * @return null
     */
    public function setUp()
    {
        require_once __DIR__.'/../../../app/AppKernel.php';

        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();
        $this->container = $this->kernel->getContainer();
        $this->dataManager = $this->container->get('app.dataManager');

        $this->clearStorage();

        parent::setUp();
    }
    /**
     * @return null
     */
    public function tearDown()
    {
        $this->kernel->shutdown();
        parent::tearDown();
    }
    /**
     * Clear all stored data
     */
    protected function clearStorage()
    {
        $this->dataManager->clearAllData();
    }
}