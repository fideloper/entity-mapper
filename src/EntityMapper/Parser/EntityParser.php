<?php  namespace EntityMapper\Parser; 

use ReflectionClass;
use EntityMapper\Reflector\Entity;

class EntityParser implements ParserInterface {

    use CommentParser;

    public function parse(ReflectionClass $class)
    {
        $comment = $class->getDocComment();
        $comment = $this->cleanInput($comment);
        $tags = $this->splitComment($comment);
        $tags = $this->parseTags($tags);

        $tableName = $this->getTable($tags, $class);
        $repository = $this->getRepository($tags);

        return new Entity($class, $tableName, $repository);
    }

    protected function getTable($tags, $class)
    {
        if( isset($tags['table']) )
        {
            $table = $tags['table'];
        } else {
            $table = $this->camelToUnderscore( $class->getShortName() );
        }

        return $table;
    }

    protected function getRepository($tags)
    {
        if( isset($tags['repository']) )
        {
            $repository = $tags['repository'];
        } else {
            // Some way to signify its a default
            $repository = 'base';
        }

        return $repository;
    }
} 