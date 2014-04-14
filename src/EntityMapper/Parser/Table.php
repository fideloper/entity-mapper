<?php  namespace EntityMapper\Parser; 

class Table {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $repository;

    public function __construct($name, $repository)
    {
        $this->name = $name;
        $this->repository = $repository;
    }

    public function name()
    {
        return $this->name;
    }

    public function repository()
    {
        return $this->repository;
    }
} 