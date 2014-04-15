<?php

use EntityMapper\Parser\MethodParser;

class MethodParserTest extends TestCase {

    public function testMethodsAreParsed()
    {
        $parser = new MethodParser;
        $reflectionClass = new ReflectionClass('\User');

        $methods = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\MethodCollection', $methods );
        $this->assertEquals( 'setVotes', $methods->setter('votes')->method() );
        $this->assertEquals( 'votes', $methods->setter('votes')->variable() );
    }

    public function testGetterAndSetterMethodsIsParsed()
    {
        $parser = new MethodParser;
        $reflectionClass = new ReflectionClass('\User');

        $methods = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\MethodCollection', $methods );

        $this->assertEquals( 'id', $methods->setter('id')->method() );
        $this->assertEquals( 'id', $methods->setter('id')->variable() );

        $this->assertEquals( 'id', $methods->getter('id')->method() );
        $this->assertEquals( 'id', $methods->getter('id')->variable() );
    }
}