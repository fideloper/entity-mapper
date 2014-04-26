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
        $this->assertEquals( 'id', $properties->property('id')->property() );
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
        $this->assertEquals( 'name', $properties->property('name')->property() );
        $this->assertEquals( 'string', $properties->property('name')->type() );
        $this->assertFalse( $properties->property('name')->isId() );
        $this->assertFalse( $properties->property('name')->isValueObject() );
    }

    public function testValueObjectPropertyIsParsed()
    {
        $parser = new PropertyParser;
        $reflectionClass = new ReflectionClass('\User');

        $properties = $parser->parse($reflectionClass);

        $this->assertEquals( 'email', $properties->property('email')->column() );
        $this->assertTrue( $properties->property('email')->isValueObject() );
        $this->assertEquals( '\Email', $properties->property('email')->type() );
    }

    public function testRelationPropertiesParsed()
    {
        $parser = new PropertyParser;
        $reflectionClass = new ReflectionClass('\User');

        $properties = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\Relation', $properties->relation('posts') );
        $this->assertEquals( null, $properties->relation('posts')->column() );
        $this->assertEquals( 'posts', $properties->relation('posts')->property() );
        $this->assertEquals( 'hasOne', $properties->relation('posts')->relation() );
        $this->assertEquals( '\Post', $properties->relation('posts')->classname() );
        $this->assertEquals( '\Post', $properties->relation('posts')->type(), 'Test type() is alias for classname()' );
    }

    public function testRelationsCanAllBeRetrieved()
    {
        $parser = new PropertyParser;
        $reflectionClass = new ReflectionClass('\User');

        $properties = $parser->parse($reflectionClass);

        $this->assertInstanceof( 'Illuminate\Support\Collection', $properties->relations() );
        $this->assertTrue( count($properties->relations()) > 0 );
        $this->assertInstanceof( '\EntityMapper\Reflector\Relation', $properties->relations()->first() );
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