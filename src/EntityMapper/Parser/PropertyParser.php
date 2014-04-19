<?php  namespace EntityMapper\Parser;

use EntityMapper\Reflector\PropertyCollection;
use ReflectionClass;
use ReflectionProperty;
use EntityMapper\Reflector\Property;

class PropertyParser implements ParserInterface {

    use CommentParser;

    /**
     * A concrete class instance
     * @var mixed
     */
    protected $concreteClass;

    public function parse(ReflectionClass $class, $concreteClass=null)
    {
        if( is_object($concreteClass) )
        {
            $this->concreteClass = $concreteClass;
        }

        $properties = $class->getProperties();

        return $this->parseProperties( $properties );
    }

    protected function parseProperties( Array $properties )
    {
        $propertyCollection = new PropertyCollection;
        foreach( $properties as $property )
        {
            $column = $this->parseAttribute( $property );

            // Only of Property has the @column definition
            // properly defined and with a column table
            if( ! is_null($column) )
            {
                $propertyCollection->addProperty($column);
            }
        }

        return $propertyCollection;
    }

    protected function parseAttribute( ReflectionProperty $property )
    {
        $comment = $property->getDocComment();
        $comment = $this->cleanInput($comment);
        $tags = $this->splitComment($comment);
        $tags = $this->parseTags($tags);

        // Bail out if there's no column table defined
        if( ! array_key_exists('column', $tags) )
        {
            return null;
        }

        $columnName = $this->getName($tags);
        $variableName = $property->getName();
        $type = $this->getType( $tags, $property );
        $isId = $this->getIsId( $tags );
        $isValueObject = $this->getIsValueObject( $type );

        return new Property($columnName, $variableName, $type, $isId, $isValueObject);
    }

    /**
     * Get variable type, useful for using Value Objects.
     *
     * TODO: Work for classnames which aren't full qualified!
     *       For example, if `@var Namespace\Class` is set in a
     *       class with namespace \Some, the real classname
     *       is actually \Some\Namespace\Class
     * @param $tags
     * @param ReflectionProperty $property
     * @return string
     */
    protected function getType($tags, ReflectionProperty $property)
    {
        if( isset($tags['var']) )
        {
            $type = $tags['var'];
        } else {
            $type = $this->guessType($property);
        }

        return $type;
    }

    /**
     * Guess property type if not given
     *
     * @param ReflectionProperty $property
     * @return string
     */
    protected function guessType(ReflectionProperty $property)
    {
        // Guess a string if we can't tell
        if( is_null($this->concreteClass) ) return 'string';

        $property->setAccessible(true);
        $propertyValue = $property->getValue( $this->concreteClass );

        if( is_null($propertyValue) || empty($propertyValue) )
        {
            return 'string';
        }

        if( is_object($propertyValue) )
        {
            return get_class($propertyValue);
        }

        return gettype($propertyValue);
    }

    protected function getIsId($tags)
    {
        return array_key_exists('id', $tags);
    }

    protected function getIsValueObject($type)
    {
        return class_exists($type);
    }

    protected function getName($tags)
    {
        if( is_null($tags['column']) || empty($tags['column']) )
        {
            throw new \DomainException('Property table must be defined');
        }

        return $tags['column'];
    }


}