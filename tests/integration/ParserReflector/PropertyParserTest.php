<?php 

use EntityMapper\Parser\PropertyParser;

class PropertyParserTest extends TestCase {

    public function testIdPropertyIsParsed()
    {
        $parser = new PropertyParser;
        $reflectionClass = new ReflectionClass('\User');

        $properties = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\PropertyCollection', $properties );
        $this->assertEquals( 'id', $properties->property('id')->column() );
        $this->assertEquals( 'id', $properties->property('id')->variable() );
        $this->assertEquals( 'integer', $properties->property('id')->type() );
        $this->assertTrue( $properties->property('id')->isId() );
        $this->assertFalse( $properties->property('id')->isValueObject() );
    }

    public function testStringPropertyIsParsed()
    {
        $parser = new PropertyParser;
        $reflectionClass = new ReflectionClass('\User');

        $properties = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\PropertyCollection', $properties );
        $this->assertEquals( 'username', $properties->property('name')->column() , 'Test property name can be different from variable name');
        $this->assertEquals( 'name', $properties->property('name')->variable() );
        $this->assertEquals( 'string', $properties->property('name')->type() );
        $this->assertFalse( $properties->property('name')->isId() );
        $this->assertFalse( $properties->property('name')->isValueObject() );
    }

    public function testValueObjectPropertyIsParsed()
    {
        $parser = new PropertyParser;
        $reflectionClass = new ReflectionClass('\User');

        $properties = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\PropertyCollection', $properties );
        $this->assertEquals( 'email', $properties->property('email')->column() , 'Test column table can be different from property table');
    }

    public function testHydratedObjectGuessesType()
    {
        $parser = new PropertyParser;
        $hydratedClass = new HydratedObjectStub('Christopher', 29, 'Lloyd');
        $reflectionClass = new ReflectionClass($hydratedClass);

        $properties = $parser->parse($reflectionClass, $hydratedClass);

        $this->assertEquals( 'integer', $properties->property('age')->type() , 'Test property with no @var still guesses column type with concrete class');
    }

    public function testAttributeWithoutColumnIsNotAProperty()
    {
        $parser = new PropertyParser;
        $hydratedClass = new HydratedObjectStub('Christopher', 29, 'Lloyd');
        $reflectionClass = new ReflectionClass($hydratedClass);

        $properties = $parser->parse($reflectionClass, $hydratedClass);

        $this->assertTrue( is_null($properties->property('middleName')) );
    }

    /**
     * @expectedException \DomainException
     */
    public function testEmptyColumnThrowsException()
    {
        $parser = new PropertyParser;
        $emptyColumnClass = new EmptyColumnStub;
        $reflectionClass = new ReflectionClass($emptyColumnClass);

        $parser->parse($reflectionClass, $emptyColumnClass);
    }

}

class EmptyColumnStub {

    /**
     * @column
     * @var string
     */
    protected $anonymousColumn;
}

class HydratedObjectStub {

    /**
     * @column $table
     * @var string
     */
    protected $name;

    /**
     * // No Variable here, also this
     * // should not break the parser
     * @column age
     */
    protected $age;

    /**
     * Not a column
     * @var string
     */
    protected $middleName;

    public function __construct($name, $age, $middleName)
    {
        $this->name = $name;
        $this->age = $age;
        $this->middleName = $middleName;
    }
}