<?php

use EntityMapper\Parser\MethodParser;

class MethodParserTest extends TestCase {

    public function testMethodsAreParsed()
    {
        $parser = new MethodParser;
        $reflectionClass = new ReflectionClass('\User');

        $methods = $parser->parse($reflectionClass);

        $this->assertTrue( is_array($methods) );
        $this->assertEquals( 'setVotes', $methods['setters']['votes']->method() );
        $this->assertEquals( 'votes', $methods['setters']['votes']->variable() );
    }

    public function testGetterAndSetterMethodsIsParsed()
    {
        $parser = new MethodParser;
        $reflectionClass = new ReflectionClass('\User');

        $methods = $parser->parse($reflectionClass);

        $this->assertTrue( is_array($methods) );

        $this->assertEquals( 'id', $methods['setters']['id']->method() );
        $this->assertEquals( 'id', $methods['setters']['id']->variable() );

        $this->assertEquals( 'id', $methods['getters']['id']->method() );
        $this->assertEquals( 'id', $methods['getters']['id']->variable() );
    }
}