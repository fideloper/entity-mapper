<?php  namespace EntityMapper\Reflector; 

abstract class BaseMethod {

    /**
     * The method table used
     * @var string
     */
    protected $method;

    /**
     * The variable this is a getter for
     * @var string
     */
    protected $variable;

    public function __construct($method, $variable)
    {
        $this->method = $method;
        $this->variable = $variable;
    }

    public function method()
    {
        return $this->method;
    }

    public function variable()
    {
        return $this->variable;
    }
} 