<?php

namespace App\RestBundle\Mock;

use App\CoreBundle\Entity\Entity;
use App\CoreBundle\Entity\Phone;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Repository
 * @package App\RestBundle\Mock
 *
 * Class mock to use before StorageManager / storage in files is fully developed
 */
class Repository
{
    /**
     * @var DataManager
     */
    protected $dataManager;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var ArrayCollection
     */
    protected $entities;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var Filesystem
     */
    protected $fs;

    public function __construct($dataManager, $className)
    {
        $this->dataManager = $dataManager;
        $this->className = $className;

        // Prepare the absolute path to dir where to store files
        $exploded = explode('\\', $className);
        $this->rootDir = $this->dataManager->getStorageRootDir() . DIRECTORY_SEPARATOR .
            strtolower(array_pop($exploded)) . DIRECTORY_SEPARATOR;

        $this->fs = new Filesystem();

        $this->entities = $this->getEntitiesFromCache();
    }

    public function find($id)
    {
        if ($this->entityExists($id)) {
            return $this->entities->filter(
                function ($entity) use ($id) {
                    return $entity->getId() == $id;
                }
            )->first();
        } else {
            return null;
        }
    }

    public function findAll()
    {
        return $this->entities;
    }

    public function persist(Entity $entity)
    {
        if ($entity->getId() !== null) {
            if (!$this->entityExists($entity->getId())) {
                throw new \Exception('Trying to update non-existing entity');
            }
        } else {
            $entity->setId($this->findNewId());
        }

        $this->entities[$entity->getId()] = $entity;
        $this->persistEntityInCache($entity);
    }

    public function remove(Entity $entity)
    {
        if ($entity->getId() !== null &&
            $this->entityExists($entity->getId())) {
            unset($this->entities[$entity->getId()]);
        } else {
            throw new \Exception('Trying to delete entity without an ID / non-existing ID');
        }

        $this->removeEntityFromCache($entity);
    }

    /**
     * Write a file corresponding to a single entity
     * @param Entity $entity
     */
    private function persistEntityInCache(Entity $entity)
    {
        $filename = $this->rootDir . $entity->getId() . '.json';
        $this->fs->dumpFile($filename, $this->dataManager->getSerializer()->serialize($entity, 'json'));
    }

    /**
     * Remove a file corresponding to a single entity
     * @param Entity $entity
     */
    private function removeEntityFromCache(Entity $entity)
    {
        $filename = $this->rootDir . $entity->getId() . '.json';
        if ($this->fs->exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * Load all the entities and prepare an array collection
     * @return ArrayCollection
     */
    private function getEntitiesFromCache()
    {
        $entities = new ArrayCollection();
        if ($this->fs->exists($this->rootDir)) {
            $filenames = scandir($this->rootDir);
            foreach ($filenames as $filename) {
                $fileContent = file_get_contents($this->rootDir . $filename);
                $entity = $this->dataManager->getSerializer()->deserialize($fileContent, $this->className, 'json');

                if ($entity instanceof Entity &&
                    $entity->getId() == $filename) {
                    $entities->add($entity);
                }
            }
        }

        return $entities;
    }

    /**
     * Check if given ID exists in the collection of entities
     * @return boolean
     */
    private function entityExists($id)
    {
        return $this->entities->exists(
            function ($key, $entry) use ($id) {
                return $entry->getId() == $id;
            }
        );
    }

    /**
     * Returns an ID for a new entity, based on existing IDs for this type of entity
     * @return integer
     */
    private function findNewId()
    {
        $id = 0;
        if ($this->fs->exists($this->rootDir)) {
            $filenames = scandir($this->rootDir);
            foreach ($filenames as $filename) {
                if (intval($filename) > $id) {
                    $id = intval($filename);
                }
            }
        }

        return $id + 1;
    }
}