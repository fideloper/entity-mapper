<?php

class NoCommentStub {}

use EntityMapper\Parser\TableParser;

class TableParserTest extends TestCase {

    public function testTableIsParsed()
    {
        $parser = new TableParser;
        $reflectionClass = new ReflectionClass('\User');

        $table = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\Table', $table );
        $this->assertEquals( 'users', $table->name() );
        $this->assertEquals( '\UserRepository', $table->repository() );
    }

    public function testNoTableCommentStub()
    {
        $parser = new TableParser;
        $reflectionClass = new ReflectionClass('NoCommentStub');

        $table = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\Table', $table );
        $this->assertEquals( 'no_comment_stub', $table->name() );
        $this->assertEquals( 'base', $table->repository() );
    }


}