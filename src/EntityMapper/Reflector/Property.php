<?php  namespace EntityMapper\Reflector;

class Property implements PropertyInterface {

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $variableName;

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

    public function __construct($column, $variableName, $type, $isId, $isValueObject)
    {
        $this->column = $column;
        $this->variableName = $variableName;
        $this->type = $type;
        $this->isId = $isId;
        $this->isValueObject = $isValueObject;
    }

    public function column()
    {
        return $this->column;
    }

    public function variable()
    {
        return $this->variableName;
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