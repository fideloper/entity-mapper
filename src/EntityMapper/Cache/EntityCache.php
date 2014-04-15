<?php  namespace EntityMapper\Cache; 

use EntityMapper\ClassInflector;

class EntityCache {

    /**
     * @var ClassInflector
     */
    protected $entityInflector;

    public function __construct(ClassInflector $entityInflector)
    {
        $this->entityInflector = $entityInflector;
    }

    /**
     * // TODO: Actually check/set cache with the Entity
     * // TODO: Allow Laravel's "debug" configuration to decide if cache is on or not
     * @param $entityClassName
     * @return \EntityMapper\Entity
     */
    public function get($entityClassName)
    {
        return $this->entityInflector->inflect($entityClassName);
    }
} 