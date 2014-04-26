<?php  namespace EntityMapper\Cache;

use EntityMapper\ClassInflector;

class NullEntityCache implements EntityCacheInterface {

    /**
     * @var \EntityMapper\ClassInflector
     */
    protected $entityInflector;

    public function __construct(ClassInflector $entityInflector)
    {
        $this->entityInflector = $entityInflector;
    }

     /**
     * Retrieve Entity from cache, or
     * refresh the cache with new Entity
     *
     * @param string $entityClassName
     * @return \EntityMapper\Entity
     */
    public function get($entityClassName)
    {
        return $this->entityInflector->inflect($entityClassName);
    }

}