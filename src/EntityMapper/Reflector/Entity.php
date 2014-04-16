<?php  namespace EntityMapper\Reflector;

class Entity {

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $repository;

    /**
     * @var PropertyCollection
     */
    protected $properties;

    /**
     * @var MethodCollection
     */
    protected $methods;

    public function __construct($table, $repository)
    {
        $this->table = $table;
        $this->repository = $repository;
    }

    public function table()
    {
        return $this->table;
    }

    public function repository()
    {
        return $this->repository;
    }

    public function properties()
    {
        return $this->properties;
    }

    public function methods()
    {
        return $this->methods;
    }

    public function setProperties(PropertyCollection $properties)
    {
        $this->properties = $properties;

        return $this;
    }

    public function setMethods(MethodCollection $methods)
    {
        $this->methods = $methods;

        return $this;
    }
} 