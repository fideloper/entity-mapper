<?php 

use EntityMapper\Parser\ColumnParser;

class ColumnParserTest extends TestCase {

    public function testIdColumnIsParsed()
    {
        $parser = new ColumnParser;
        $reflectionClass = new ReflectionClass('\User');

        $columns = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\ColumnCollection', $columns );
        $this->assertEquals( 'id', $columns->column('id')->name() );
        $this->assertEquals( 'id', $columns->column('id')->variable() );
        $this->assertEquals( 'integer', $columns->column('id')->type() );
        $this->assertTrue( $columns->column('id')->isId() );
        $this->assertFalse( $columns->column('id')->isValueObject() );
    }

    public function testStringColumnIsParsed()
    {
        $parser = new ColumnParser;
        $reflectionClass = new ReflectionClass('\User');

        $columns = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\ColumnCollection', $columns );
        $this->assertEquals( 'username', $columns->column('name')->name() , 'Test column name can be different from variable name');
        $this->assertEquals( 'name', $columns->column('name')->variable() );
        $this->assertEquals( 'string', $columns->column('name')->type() );
        $this->assertFalse( $columns->column('name')->isId() );
        $this->assertFalse( $columns->column('name')->isValueObject() );
    }

    public function testValueObjectColumnIsParsed()
    {
        $parser = new ColumnParser;
        $reflectionClass = new ReflectionClass('\User');

        $columns = $parser->parse($reflectionClass);

        $this->assertInstanceof( '\EntityMapper\Reflector\ColumnCollection', $columns );
        $this->assertEquals( 'email', $columns->column('email')->name() , 'Test column name can be different from variable name');
    }

    public function testHydratedObjectGuessesType()
    {
        $parser = new ColumnParser;
        $hydratedClass = new HydratedObjectStub('Christopher', 29, 'Lloyd');
        $reflectionClass = new ReflectionClass($hydratedClass);

        $columns = $parser->parse($reflectionClass, $hydratedClass);

        $this->assertEquals( 'integer', $columns->column('age')->type() , 'Test column with no @var still guesses type with concrete class');
    }

    public function testAttributeWithoutColumnIsNotAColumn()
    {
        $parser = new ColumnParser;
        $hydratedClass = new HydratedObjectStub('Christopher', 29, 'Lloyd');
        $reflectionClass = new ReflectionClass($hydratedClass);

        $columns = $parser->parse($reflectionClass, $hydratedClass);

        $this->assertTrue( is_null($columns->column('middleName')) );
    }

    /**
     * @expectedException \DomainException
     */
    public function testEmptyColumnThrowsException()
    {
        $parser = new ColumnParser;
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