<?php  namespace EntityMapper\Reflector;

use ReflectionClass;

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

    /**
     * @var \ReflectionClass
     */
    private $reflector;

    public function __construct(ReflectionClass $reflector, $table, $repository)
    {
        $this->reflector = $reflector;
        $this->table = $table;
        $this->repository = $repository;
    }

    public function reflector()
    {
        return $this->reflector;
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