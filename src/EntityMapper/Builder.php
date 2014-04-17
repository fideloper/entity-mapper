<?php  namespace EntityMapper;

use EntityMapper\Reflector\Entity;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder {

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $query;

    /**
     * @var Reflector\Entity
     */
    private $entity;

    /**
     * @var Reflector\EntityMapper
     */
    private $entityMapper;

    /**
     * The methods that should be returned from query builder.
     *
     * @var array
     */
    protected $passthru = array(
        'toSql', 'lists', 'insert', 'insertGetId', 'pluck', 'count',
        'min', 'max', 'avg', 'sum', 'exists', 'getBindings',
    );

    public function __construct(QueryBuilder $query, Entity $entity, EntityMapper $entityMapper)
    {
        $this->query = $query;
        $this->entity = $entity;
        $this->entityMapper = $entityMapper;
    }

    public function find($id, $columns = ['*'])
    {
        if (is_array($id))
        {
            return $this->findMany($id, $columns);
        }

        // Need to get ID column
        $this->query->where($this->entity->properties()->idProperty()->name(), '=', $id);

        return $this->first($columns);
    }

    public function findMany(array $id, $columns = ['*'])
    {
        if (empty($id)) return new Collection;

        // Need to get ID column
        $this->query->whereIn($this->entity->properties()->idProperty()->name(), $id);

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

    /**
     * Execute the query as a "select" statement.
     *
     * @param array $columns
     * @return array|static[]
     */
    public function get($columns = ['*'])
    {
        $entities = $this->getEntities($columns);

        // TODO: Eager load relationships here in DA FUTURE!

        return new Collection($entities);
    }

    /**
     * Hydrate entities with results
     * @param $columns
     * @return array|static[]
     */
    public function getEntities($columns)
    {
        $results = $this->query->get($columns);

        // Magic
        $entities = $this->entityMapper->hydrate($this->entity, $results);

        return $entities;
    }

    /**
     * Set the table to perform selects upon
     * @param $table
     */
    public function setTable($table)
    {
        $this->from($table);
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
        $result = call_user_func_array(array($this->query, $method), $parameters);

        return in_array($method, $this->passthru) ? $result : $this;
    }
}