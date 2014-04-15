<?php 

use EntityMapper\Parser\ColumnParser;

class ColumnParserTest extends TestCase {

    public function testIdColumnIsParsed()
    {
        $parser = new ColumnParser;
        $reflectionClass = new ReflectionClass('\User');

        $columns = $parser->parse($reflectionClass);

        $this->assertTrue( is_array($columns) );
        $this->assertEquals( 'id', $columns['id']->name() );
        $this->assertEquals( 'id', $columns['id']->variable() );
        $this->assertEquals( 'integer', $columns['id']->type() );
        $this->assertTrue( $columns['id']->isId() );
        $this->assertFalse( $columns['id']->isValueObject() );
    }

    public function testStringColumnIsParsed()
    {
        $parser = new ColumnParser;
        $reflectionClass = new ReflectionClass('\User');

        $columns = $parser->parse($reflectionClass);

        $this->assertTrue( is_array($columns) );
        $this->assertEquals( 'username', $columns['name']->name() , 'Test column name can be different from variable name');
        $this->assertEquals( 'name', $columns['name']->variable() );
        $this->assertEquals( 'string', $columns['name']->type() );
        $this->assertFalse( $columns['name']->isId() );
        $this->assertFalse( $columns['name']->isValueObject() );
    }

    public function testValueObjectColumnIsParsed()
    {
        $parser = new ColumnParser;
        $reflectionClass = new ReflectionClass('\User');

        $columns = $parser->parse($reflectionClass);

        $this->assertTrue( is_array($columns) );
        $this->assertEquals( 'email', $columns['email']->name() , 'Test column name can be different from variable name');
    }

    public function testHydratedObjectGuessesType()
    {
        $parser = new ColumnParser;
        $hydratedClass = new HydratedObjectStub('Christopher', 29, 'Lloyd');
        $reflectionClass = new ReflectionClass($hydratedClass);

        $columns = $parser->parse($reflectionClass, $hydratedClass);

        $this->assertEquals( 'integer', $columns['age']->type() , 'Test column with no @var still guesses type with concrete class');
    }

    public function testAttributeWithoutColumnIsNotAColumn()
    {
        $parser = new ColumnParser;
        $hydratedClass = new HydratedObjectStub('Christopher', 29, 'Lloyd');
        $reflectionClass = new ReflectionClass($hydratedClass);

        $columns = $parser->parse($reflectionClass, $hydratedClass);

        $this->assertFalse( isset($columns['middleName']) );
    }

}

class HydratedObjectStub {

    /**
     * @column $name
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