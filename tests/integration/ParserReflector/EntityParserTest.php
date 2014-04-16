<?php

class NoCommentStub {}

use EntityMapper\Parser\EntityParser;

class EntityParserTest extends TestCase {

    public function testEntityIsParsed()
    {
        $parser = new EntityParser;
        $reflectionClass = new ReflectionClass('\User');

        $entity = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\Entity', $entity );
        $this->assertEquals( 'users', $entity->table() );
        $this->assertEquals( '\UserRepository', $entity->repository() );
    }

    public function testNoEntityCommentStub()
    {
        $parser = new EntityParser;
        $reflectionClass = new ReflectionClass('NoCommentStub');

        $entity = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\Entity', $entity );
        $this->assertEquals( 'no_comment_stub', $entity->table() );
        $this->assertEquals( 'base', $entity->repository() );
    }


}