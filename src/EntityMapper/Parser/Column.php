<?php  namespace EntityMapper\Parser; 

class Column {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $variableName;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $isId;

    /**
     * @var bool
     */
    private $isValueObject;

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

    public function variableName()
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