<?php  namespace EntityMapper;

use ReflectionClass;
use EntityMapper\Cache\EntityCache;

/**
 * Map/Hydrate an Entity
 * Class EntityMapper
 * @package EntityMapper
 */
class EntityMapper {

    /**
     * @var ClassInflector
     */
    protected $entityCache;

    /**
     * This will need an instance of Container
     * for its Container::make abilities
     *
     * @param EntityCache $entityCache
     */
    public function __construct(EntityCache $entityCache)
    {
        $this->entityCache = $entityCache;
    }

    /**
     * @param $entityClassName
     * @param $data - Array? Object? Iterable? Illuminate\Database\ResultSet?
     * @return object $entityClassName
     */
    public function hydrate($entityClassName, $data)
    {
        $inflectedEntity = $this->getEntity($entityClassName);
        $inflectedEntity->

        return $this->doHydrate($entityClassName, $inflectedEntity, $data);
    }

    /**
     * @param $entityClassName - Our Domain Entity
     * @param $inflectedEntity -
     * @param $data
     * @return object
     */
    protected function fillClass($entityClassName, $inflectedEntity, $data)
    {
        $reflectionClass = new ReflectionClass($entityClassName);

        // Add data to reflected Class

        return $reflectionClass->newInstanceWithoutConstructor();
    }

    protected function getEntity($entityClassName)
    {
        return $this->entityCache->get($entityClassName);
    }
} 