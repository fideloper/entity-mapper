<?php  namespace EntityMapper;

use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder {

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $query;

    /**
     * Classname or a concrete entity class
     * @var mixed
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

    public function __construct(QueryBuilder $query, $entity, EntityMapper $entityMapper)
    {
        $this->query = $query;
        $this->entity = $entity;
        $this->entityMapper = $entityMapper;

        $this->setTable( $this->entityMapper->table($this->entity) );
    }

    public function find($id, $columns = ['*'])
    {
        if (is_array($id))
        {
            return $this->findMany($id, $columns);
        }

        // Need to get ID column
        $this->query->where($this->entityMapper->idColumn($this->entity), '=', $id);

        return $this->first($columns);
    }

    public function findMany(array $id, $columns = ['*'])
    {
        if (empty($id)) return new Collection;

        // Need to get ID column
        $this->query->whereIn($this->entityMapper->idColumn($this->entity), $id);

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

        $entities = $this->entityMapper->create($this->entity, $results);

        return $entities;
    }

    public function save($entity)
    {
        // Gather Relations (via EntityMapper)
        $relations = $this->entityMapper->relations($entity);

        /* TODO: Finish attempting to save relationships...and oh yeah, build relationship classes...
                 1:m/m:1 and m:m will need collection-type treatment
        // Map relationships to relationship objects, each with own query builder
        // which will save the relationship properties (entites) separately
        foreach( $relations as $relationship )
        {
            // Does this belong inside of EntityMapper?
            $property = $relationship->property();
            $relatedEntity = $entity->$property;
            //  Via repository? Via EntityMapper\Builder?
            // Yes, this is a protected property!
            $builder = new static($this->query, $relatedEntity, $this->entityMapper);
            $builder->save($relatedEntity);
        }
        */

        // Save those Relations separately via their Repositories
        return $this->saveEntity($entity);
    }

    /**
     * Insert or Update a single Entity
     * @param $entity
     * @throws \DomainException
     * @return mixed
     */
    public function saveEntity($entity)
    {
        $idColumn = $this->entityMapper->idColumn($this->entity);

        $data = $this->entityMapper->dehydrate($entity);

        // Can an $id be 0 ?
        if( ! is_null($data[$idColumn]) )
        {
            // It has an id, let's update it
            return $this->query->where($idColumn, $data[$idColumn])->update($data);
        }

        $insertId = $this->query->insertGetId($data);

        if( $insertId )
        {
            $this->entityMapper->hydrate($entity, [$idColumn => $insertId]);
        }

        return $insertId;
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
     * Returns $this, instead of Illuminate\Database\Query\Builder
     * unless a passthru method is used
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