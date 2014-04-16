<?php  namespace EntityMapper;

use ReflectionClass;
use EntityMapper\Parser\PropertyParser;
use EntityMapper\Parser\MethodParser;
use EntityMapper\Parser\EntityParser;

/**
 * Class ClassInflector
 * Parse out a class
 * @package EntityMapper
 */
class ClassInflector {

    /**
     * @var Parser\EntityParser
     */
    protected $entityParser;
    /**
     * @var Parser\PropertyParser
     */
    protected $columnParser;
    /**
     * @var Parser\MethodParser
     */
    protected $methodParser;

    /**
     * Create Entity Inflector
     * @param Parser\EntityParser $entityParser
     * @param Parser\PropertyParser $columnParser
     * @param Parser\MethodParser $methodParser
     */
    public function __construct(EntityParser $entityParser, PropertyParser $columnParser, MethodParser $methodParser)
    {
        $this->entityParser = $entityParser;
        $this->columnParser = $columnParser;
        $this->methodParser = $methodParser;
    }

    public function inflect($entity)
    {
        $reflectClass = new ReflectionClass($entity);

        $entity = $this->entityParser->parse( $reflectClass );
        $entity->setColumns( $this->columnParser->parse( $reflectClass ) );
        $entity->setMethods( $this->methodParser->parse( $reflectClass ) );

        return $entity;
    }
} 