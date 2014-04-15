<?php  namespace EntityMapper\Parser; 

use EntityMapper\Reflector\ColumnCollection;
use ReflectionClass;
use ReflectionProperty;
use EntityMapper\Reflector\Column;

class ColumnParser implements ParserInterface {

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

        return $this->parseAttributes( $properties );
    }

    protected function parseAttributes( Array $properties )
    {
        $columns = new ColumnCollection;
        foreach( $properties as $property )
        {
            $column = $this->parseAttribute( $property );

            // Only of Property has the @column definition
            // properly defined and with a column name
            if( ! is_null($column) )
            {
                $columns->addColumn($column->variable(), $column);
            }
        }

        return $columns;
    }

    protected function parseAttribute( ReflectionProperty $property )
    {
        $comment = $property->getDocComment();
        $comment = $this->cleanInput($comment);
        $tags = $this->splitComment($comment);
        $tags = $this->parseTags($tags);

        // Bail out if there's no column name defined
        if( ! array_key_exists('column', $tags) )
        {
            return null;
        }

        $columnName = $this->getName($tags);
        $variableName = $property->getName();
        $type = $this->getType( $tags, $property );
        $isId = $this->getIsId( $tags );
        $isValueObject = $this->getIsValueObject( $type );

        return new Column($columnName, $variableName, $type, $isId, $isValueObject);
    }

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
        return isset($tags['id']);
    }

    protected function getIsValueObject($type)
    {
        return class_exists($type);
    }

    protected function getName($tags)
    {
        if( is_null($tags['column']) || empty($tags['column']) )
        {
            throw new \DomainException('Column name must be defined');
        }

        return $tags['column'];
    }


} 