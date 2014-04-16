<?php  namespace EntityMapper\Reflector;

class Property {

    /**
     * @var string
     */
    protected $name;

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

    public function __construct($name, $variableName, $type, $isId, $isValueObject)
    {
        $this->name = $name;
        $this->variableName = $variableName;
        $this->type = $type;
        $this->isId = $isId;
        $this->isValueObject = $isValueObject;
    }

    public function name()
    {
        return $this->name;
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