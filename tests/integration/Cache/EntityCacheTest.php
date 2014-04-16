<?php

use EntityMapper\Cache\EntityCache;
use EntityMapper\ClassInflector;
use EntityMapper\Parser\PropertyParser;
use EntityMapper\Parser\MethodParser;
use EntityMapper\Parser\EntityParser;

class EntityCacheTest extends TestCase {

    public function testCacheCaches()
    {
        $cache = $this->getCache();

        // This only works because we haven't implemented a real cache yet
        $entity = $cache->get('\User');

        $this->assertInstanceof( '\EntityMapper\Reflector\Entity', $entity );
        $this->assertEquals( 'users', $entity->table() );
    }

    protected function getCache()
    {
        return new EntityCache(
            new ClassInflector(
                new EntityParser,
                new PropertyParser,
                new MethodParser)
        );
    }
} 