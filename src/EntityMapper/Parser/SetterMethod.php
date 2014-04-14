<?php  namespace EntityMapper\Parser; 

class SetterMethod {

    /**
     * The variable this is a setter for
     * @var string
     */
    protected $variable;

    public function __construct($variable)
    {
        $this->variable = $variable;
    }

    public function variable()
    {
        return $this->variable;
    }
} 