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
        $this->class = $classname;
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
        return $this->name;
    }
} 