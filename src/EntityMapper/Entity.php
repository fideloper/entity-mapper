<?php  namespace EntityMapper; 

use EntityMapper\Reflector\Table;

class Entity {

    /**
     * Reflected class
     * @var string
     */
    protected $className;

    /**
     * Table and Repository
     * @var \EntityMapper\Table
     */
    protected $table;

    /**
     * Column Data
     * @var Array
     */

    protected $columns;

    /**
     * Method Getters/Setters
     * @var Array
     */
    protected $methods;

    public function __construct($className, Table $table, Array $columns, Array $methods)
    {
        if( is_object($className) )
        {
            $className = get_class($className);
        }

        $this->$className = $className;
        $this->table = $table;
        $this->columns = $columns;
        $this->methods = $methods;
    }

    public function table()
    {
        return $this->table->name();
    }

    public function repository()
    {
        return $this->table->repository();
    }

    public function idColumns()
    {
        $ids = [];
        foreach( $this->columns as $column )
        {
            if( $column->isId() )
            {
                $ids[] = $column;
            }
        }

        return $ids;
    }

    public function column($variableName)
    {
        if( ! isset($this->columns[$variableName]) ) {
            throw new \InvalidArgumentException('Variable '.$variableName.' does not have an associated column.');
        }

        return $this->columns[$variableName];
    }

    public function setter($variableName)
    {
        if( ! isset($this->methods['setters'][$variableName]) ) {
            return null;
        }

        return $this->methods['setters'][$variableName];
    }

    public function getter($variableName)
    {
        if( ! isset($this->methods['getters'][$variableName]) ) {
            return null;
        }

        return $this->methods['getters'][$variableName];
    }
} 