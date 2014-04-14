<?php  namespace EntityMapper\Parser; 

use ReflectionClass;

class TableParser extends CommentParser {
    /**
     * Table used for model
     * @var string
     */
    protected $table;

    /**
     * Repository specified by class
     * @var string
     */
    protected $repository;

    public function getTable(ReflectionClass $class)
    {
        if( ! is_null($this->table) ) return $this->table;

        if( isset($this->tags['table']) )
        {
            $this->table = $this->tags['table'];
        } else {
            $this->table = $this->camelToUnderscore( $class->getShortName() );
        }

        return $this->table;
    }

    public function getRepository(ReflectionClass $class)
    {
        if( ! is_null($this->repository) ) return $this->repository;

        if( isset($this->tags['repository']) )
        {
            $this->repository = $this->tags['repository'];
        } else {
            // Some way to signify its a default
            $this->repository = $class->getShortName().'Repository';
        }
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