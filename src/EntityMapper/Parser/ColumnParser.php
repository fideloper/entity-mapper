<?php  namespace EntityMapper\Parser; 

use ReflectionClass;
use ReflectionProperty;

class ColumnParser {

    use CommentParser;

    public function parse(ReflectionClass $class)
    {
        $properties = $class->getProperties();

        return $this->parseAttributes( $properties );
    }

    protected function parseAttributes( Array $properties )
    {
        $columns = [];
        foreach( $properties as $property )
        {
            $columns[] = $this->parseAttribute( $property );
        }

        return $columns;
    }

    protected function parseAttribute( ReflectionProperty $property )
    {
        $comment = $property->getDocComment();
        $comment = $this->cleanInput($comment);
        $tags = $this->splitComment($comment);
        $tags = $this->parseTags($tags);

        $columnName = $this->getColumnName( $tags, $property );
        $variableName = $property->getName();
        $type = $this->getType( $tags, $property );
        $isId = $this->getIsId( $tags );
        $isValueObject = $this->getIsValueObject( $tags, $property );

        return new Column($columnName, $variableName, $type, $isId, $isValueObject);
    }

    protected function getColumnName(Array $tags, ReflectionProperty $property)
    {
        if( isset($tags['column']) )
        {
            $name = $tags['column'];
        } else {
            $name = $this->camelToUnderscore( $property->getName() );
        }

        return $name;
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
        $propertyValue = $property->getValue();

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


} 