<?php

namespace App\StorageBundle\DataManager;

/**
 * Class DataManager
 * @package App\StorageBundle\DataManager
 */
class DataManager
{
    /**
     * @var array[App\StorageBundle\Repository\Repository]
     */
    protected $repositories;

    /**
     * Factory de repository
     *
     * @param $className The entity class name
     */
    public function getRepo($className)
    {
        if (array_key_exists($className, $this->repositories)) {
            return $this->repositories[$className];
        } else {
            $this->repositories[$className] = $this->createRepository($className);

            return $this->repositories[$className];
        }
    }

    /**
     * Initialize a repository depending of the class name and if a specific repository exists
     *
     * @param $className The entity class name
     */
    protected function createRepository($className)
    {

    }
}