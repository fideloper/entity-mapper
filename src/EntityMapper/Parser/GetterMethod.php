<?php  namespace EntityMapper\Parser; 

class GetterMethod {

    /**
     * The variable this is a getter for
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