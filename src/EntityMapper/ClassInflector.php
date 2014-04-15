<?php  namespace EntityMapper;

use ReflectionClass;
use EntityMapper\Parser\ColumnParser;
use EntityMapper\Parser\MethodParser;
use EntityMapper\Parser\TableParser;

/**
 * Class ClassInflector
 * Parse out a class
 * @package EntityMapper
 */
class ClassInflector {

    /**
     * @var Parser\TableParser
     */
    protected $tableParser;
    /**
     * @var Parser\ColumnParser
     */
    protected $columnParser;
    /**
     * @var Parser\MethodParser
     */
    protected $methodParser;

    /**
     * Create Entity Inflector
     * @param TableParser $tableParser
     * @param ColumnParser $columnParser
     * @param MethodParser $methodParser
     */
    public function __construct(TableParser $tableParser, ColumnParser $columnParser, MethodParser $methodParser)
    {
        $this->tableParser = $tableParser;
        $this->columnParser = $columnParser;
        $this->methodParser = $methodParser;
    }

    public function inflect($entity)
    {
        $reflectClass = new ReflectionClass($entity);

        $table = $this->tableParser->parse( $reflectClass );
        $table->setColumns( $this->columnParser->parse( $reflectClass ) );
        $table->setMethods( $this->methodParser->parse( $reflectClass ) );

        return $table;
    }
} 