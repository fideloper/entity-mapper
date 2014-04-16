<?php

use EntityMapper\Cache\EntityCache;
use EntityMapper\ClassInflector;
use EntityMapper\Parser\ColumnParser;
use EntityMapper\Parser\MethodParser;
use EntityMapper\Parser\TableParser;

class EntityCacheTest extends TestCase {

    public function testCacheCaches()
    {
        $cache = $this->getCache();

        // This only works because we haven't implemented a real cache yet
        $table = $cache->get('\User');

        $this->assertInstanceof( '\EntityMapper\Reflector\Table', $table );
        $this->assertEquals( 'users', $table->name() );
    }

    protected function getCache()
    {
        return new EntityCache(
            new ClassInflector(
                new TableParser,
                new ColumnParser,
                new MethodParser)
        );
    }
} 