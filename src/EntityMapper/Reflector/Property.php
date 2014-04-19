<?php  namespace EntityMapper\Reflector;

class Property implements PropertyInterface {

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $property;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $isId;

    /**
     * @var bool
     */
    protected $isValueObject;

    public function __construct($column, $property, $type, $isId, $isValueObject)
    {
        $this->column = $column;
        $this->property = $property;
        $this->type = $type;
        $this->isId = $isId;
        $this->isValueObject = $isValueObject;
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
        return $this->type;
    }

    public function isId()
    {
        return $this->isId;
    }

    public function isValueObject()
    {
        return $this->isValueObject;
    }
} 