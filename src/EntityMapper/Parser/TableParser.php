<?php  namespace EntityMapper\Parser; 

use ReflectionClass;

class TableParser {

    use CommentParser;

    public function parse(ReflectionClass $class)
    {
        $comment = $class->getDocComment();
        $comment = $this->cleanInput($comment);
        $tags = $this->splitComment($comment);
        $tags = $this->parseTags($tags);

        $tableName = $this->getTable($tags, $class);
        $repository = $this->getRepository($tags, $class);

        return new Table($tableName, $repository);
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

    protected function getRepository($tags, $class)
    {
        if( isset($tags['repository']) )
        {
            $repository = $tags['repository'];
        } else {
            // Some way to signify its a default
            $repository = $class->getShortName().'Repository';
        }

        return $repository;
    }
} 