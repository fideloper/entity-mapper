<?php  namespace EntityMapper; 

use EntityMapper\EntityMapper;
use Illuminate\Database\Query\Builder as BaseBuilder;

class Builder {

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $builder;

    /**
     * @var mixed
     */
    private $entityClassName;

    /**
     * @var
     */
    private $entityMapper;

    public function __construct(BaseBuilder $builder, $entityClassName, EntityMapper $entityMapper)
    {
        $this->builder = $builder;
        $this->entityClassName = $entityClassName;
        $this->entityMapper = $entityMapper;
    }

    public function findMany(array $id, $columns = ['*'])
    {

    }

    /**
     * Dynamically handle calls into the query instance.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
       return call_user_func_array(array($this->builder, $method), $parameters);
    }
}