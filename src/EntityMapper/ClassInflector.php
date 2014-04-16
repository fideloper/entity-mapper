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
    protected $propertyParser;
    /**
     * @var Parser\MethodParser
     */
    protected $methodParser;

    /**
     * Create Entity Inflector
     * @param Parser\EntityParser $entityParser
     * @param Parser\PropertyParser $propertyParser
     * @param Parser\MethodParser $methodParser
     */
    public function __construct(EntityParser $entityParser, PropertyParser $propertyParser, MethodParser $methodParser)
    {
        $this->entityParser = $entityParser;
        $this->propertyParser = $propertyParser;
        $this->methodParser = $methodParser;
    }

    public function inflect($entity)
    {
        $reflectClass = new ReflectionClass($entity);

        $entity = $this->entityParser->parse( $reflectClass );
        $entity->setProperties( $this->propertyParser->parse( $reflectClass ) );
        $entity->setMethods( $this->methodParser->parse( $reflectClass ) );

        return $entity;
    }
} 