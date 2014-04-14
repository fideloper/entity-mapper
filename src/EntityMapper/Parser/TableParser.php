<?php  namespace EntityMapper\Parser; 

use ReflectionClass;

class TableParser {

    use CommentParser;

    public function parse(ReflectionClass $class)
    {
        $comment = $class->getDocComment();
        $comment = $this->cleanInput($comment);
        $tags = $this->splitComment($comment);
        $this->tags = $this->parseTags($tags);

        $tableName = $this->getTable($class);
        $repository = $this->getRepository($class);

        return new Table($tableName, $repository);
    }

    protected function getTable(ReflectionClass $class)
    {
        if( ! is_null($this->table) ) return $this->table;

        if( isset($this->tags['table']) )
        {
            $table = $this->tags['table'];
        } else {
            $table = $this->camelToUnderscore( $class->getShortName() );
        }

        return $table;
    }

    protected function getRepository(ReflectionClass $class)
    {
        if( ! is_null($this->repository) ) return $this->repository;

        if( isset($this->tags['repository']) )
        {
            $repository = $this->tags['repository'];
        } else {
            // Some way to signify its a default
            $repository = $class->getShortName().'Repository';
        }

        return $repository;
    }

    /**
     * Convert CamelCaseClassName to underscore_class_name
     * The Underscore style class name is what we'll be
     * assuming is used for database table names
     * @param string $camelCase
     * @return string
     */
    protected function camelToUnderscore($camelCase)
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $camelCase));
    }


} 