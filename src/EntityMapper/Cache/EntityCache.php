<?php  namespace EntityMapper\Cache;

use Illuminate\Cache\CacheManager;
use EntityMapper\ClassInflector;

class EntityCache {

    /**
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * @var \EntityMapper\ClassInflector
     */
    protected $entityInflector;

    public function __construct(CacheManager $cache, ClassInflector $entityInflector)
    {
        $this->cache = $cache;
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
        $cacheKey = $this->getCacheKey($entityClassName);

        if( $this->cache->has($cacheKey) )
        {
            return $this->cache->get($cacheKey);
        }

        $entity = $this->entityInflector->inflect($entityClassName);

        $this->cache->put($cacheKey, $entity);

        return $entity;
    }

    /**
     * Parse the cache key
     * @param  mixed $entityClassName
     * @return string
     */
    protected function getCacheKey($entityClassName)
    {
        $cacheKey = 'entity.';

        if( is_string($entityClassName) )
        {
            $cacheKey = $cacheKey.$entityClassName;
        }

        if( is_object($entityClassName) )
        {
            $cacheKey = $cacheKey.get_class($entityClassName);
        }

        return $cacheKey;

    }
}