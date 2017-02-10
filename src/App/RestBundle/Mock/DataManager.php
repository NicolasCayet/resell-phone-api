<?php

namespace App\RestBundle\Mock;

use App\RestBundle\Mock\Repository;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class DataManager
 * @package App\RestBundle\Mock
 *
 * Class mock to use before DataManager / storage in files is fully developed
 */
class DataManager
{
    protected $repositories = array();

    private $storageRootDir;

    private $serializer;

    public function __construct($storageRootDir, $serializer)
    {
        $this->storageRootDir = $storageRootDir . DIRECTORY_SEPARATOR . 'tmp-mock-data' . DIRECTORY_SEPARATOR;
        $this->serializer = $serializer;
    }

    public function getStorageRootDir()
    {
        return $this->storageRootDir;
    }

    public function getSerializer()
    {
        return $this->serializer;
    }

    public function getRepo($className)
    {
        if (array_key_exists($className, $this->repositories)) {
            return $this->repositories[$className];
        } else {
            $this->repositories[$className] = new Repository($this, $className);

            return $this->repositories[$className];
        }
    }

    public function clearAllData()
    {
        $fs = new Filesystem();
        $fs->remove($this->storageRootDir);
    }
}