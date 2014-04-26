<?php  namespace EntityMapper\Reflector;

class Relation implements PropertyInterface {

    /**
     * @var string
     */
    protected $classname;

    /**
     * @var string
     */
    protected $property;

    /**
     * @var string
     */
    protected $relation;

    /**
     * @var string|null
     */
    private $column;

    public function __construct($classname, $property, $relation, $column=null)
    {
        $this->classname = $classname;
        $this->property = $property;
        $this->relation = $relation;
        $this->column = $column;
    }

    public function column()
    {
        return $this->column;
    }

    public function property()
    {
        return $this->property;
    }

    public function type()
    {
        return $this->classname();
    }

    public function isId()
    {
        return false;
    }

    public function isValueObject()
    {
        return true;
    }

    public function relation()
    {
        return $this->relation;
    }

    public function isColumn()
    {
        return ! is_null($this->column);
    }

    public function classname()
    {
        return $this->classname;
    }
} 