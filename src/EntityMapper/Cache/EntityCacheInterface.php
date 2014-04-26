<?php namespace EntityMapper\Cache;

interface EntityCacheInterface {

    /**
     * Retrieve Entity from cache, or
     * refresh the cache with new Entity
     *
     * @param string $entityClassName
     * @return \EntityMapper\Entity
     */
    public function get($entityClassName);
}