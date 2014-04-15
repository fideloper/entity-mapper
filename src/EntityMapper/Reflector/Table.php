<?php  namespace EntityMapper\Reflector;

class Table {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $repository;

    /**
     * @var ColumnCollection
     */
    protected $columns;

    /**
     * @var MethodCollection
     */
    protected $methods;

    public function __construct($name, $repository)
    {
        $this->name = $name;
        $this->repository = $repository;
    }

    public function name()
    {
        return $this->name;
    }

    public function repository()
    {
        return $this->repository;
    }

    public function columns()
    {
        return $this->columns;
    }

    public function methods()
    {
        return $this->methods;
    }

    public function setColumns(ColumnCollection $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    public function setMethods(MethodCollection $methods)
    {
        $this->methods = $methods;

        return $this;
    }
} 