<?php  namespace EntityMapper;

use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder {

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $query;

    /**
     * @var mixed
     */
    private $entityClassName;

    /**
     * @var
     */
    private $entityMapper;

    public function __construct(QueryBuilder $query, $entityClassName, EntityMapper $entityMapper)
    {
        $this->query = $query;
        $this->entityClassName = $entityClassName;
        $this->entityMapper = $entityMapper;
    }

    public function find($id, $columns = ['*'])
    {
        if (is_array($id))
        {
            return $this->findMany($id, $columns);
        }

        // Need to get ID column
        $this->query->where($this->model->getKeyName(), '=', $id);

        return $this->first($columns);
    }

    public function findMany(array $id, $columns = ['*'])
    {
        if (empty($id)) return new Collection;

        // Need to get ID column
        $this->query->whereIn($this->model->getKeyName(), $id);

        return $this->get($columns);
    }

    /**
     * Execute the query and get the first result.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public function first($columns = array('*'))
    {
        return $this->take(1)->get($columns)->first();
    }

    public function get($columns = ['*'])
    {
        return $this->query->get($columns);
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