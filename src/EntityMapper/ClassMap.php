<?php  namespace EntityMapper;

/**
 * Class ClassMap
 * Represents the parsed mapping information
 * from a given class
 * @package EntityMapper
 */
class ClassMap {

    /**
     * Reflected class
     * @var string
     */
    protected $classname;

    /**
     * Table and Repository
     * @var Array
     */
    private $table;

    /**
     * Column Data
     * @var Array
     */

    private $columns;

    /**
     * Method Getters/Setters
     * @var Array
     */
    private $methods;

    /**
     * Create a new ClassMap
     * @param $class
     * @param array $table    Associative Array
     * @param array $columns  Associative Array
     * @param array $methods  Associative Array
     */
    public function __construct($class, Array $table, Array $columns, Array $methods)
    {
        if( is_object($class) )
        {
            $class = get_class($class);
        }

        $this->classname = $class;
        $this->table = $table;
        $this->columns = $columns;
        $this->methods = $methods;
    }

    public function getTable()
    {
        return $this->table['table'];
    }

    public function getRepository()
    {
        return $this->table['repository'];
    }

    public function getIdColumns()
    {

    }
} 