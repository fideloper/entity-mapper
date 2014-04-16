<?php  namespace EntityMapper;

use Illuminate\Container\Container;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\ConnectionResolverInterface as Resolver;

class Repository {

    protected static $app;

    /**
     * @var mixed
     */
    protected $entity;

    /**
     * @var EntityMapper
     */
    protected $mapper;

    /**
     * Database connection
     * @var string
     */
    protected $connection;

    /**
     * The connection resolver instance.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected static $resolver;

    public function all($columns = ['*'])
    {
        return $this->query()->get($columns);
    }

    public function find($id, $columns = ['*'])
    {
        if( is_array($id) )
        {
            return $this->query()->findMany($id, $columns);
        }

        return $this->query()->find($id, $columns);
    }

    public function findOrFail($id, $columns = ['*'])
    {
        if ( ! is_null($entity = $this->query()->find($id, $columns)) ) return $entity;

        throw new EntityNotFoundException(get_class($entity));
    }

    /*
     * public function create(array $data) {} - Factory??
     * public function firstByAttributess($attributes) {}
     * */

    public function save($entity) {}
    public function delete($entity) {}
    public function touch($entity) {}


    /**
     * Set Entity class/class name
     * @param mixed $entityClassName
     */
    public function setEntity($entityClassName)
    {
        $this->entity = $entityClassName;
    }

    /**
     * Set mapper for this class
     * @param EntityMapper $mapper
     */
    public function setMapper(EntityMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * Get new Query Builder
     * To build entities
     * @return Builder
     */
    public function query()
    {
        return new Builder( $this->newBaseQueryBuilder(), $this->entity, $this->mapper );
    }

    /**
     * Get a new query builder instance for the connection.
     * Used in the EntityMapper\Builder
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new QueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }

    // Date Logic?

    public function on($connection = null)
    {

    }

    public function getConnection()
    {
        return static::resolveConnection($this->connection);
    }

    public function getConnectionName()
    {
        return $this->connection;
    }

    public function setConnection($name)
    {
        $this->connection = $name;
    }

    public static function setConnectionResolver(Resolver $resolver)
    {
        static::$resolver = $resolver;
    }

    public static function getConnectionResolver()
    {
        return static::$resolver;
    }

    public static function resolveConnection($connection = null)
    {
        return static::$resolver->connection($connection);
    }

    /**
     * Set application container
     * used to create repository and dependencies
     * @param Container $app
     */
    public static function setApp(Container $app)
    {
        static::$app = $app;
    }

    /**
     * Get application container
     * @return mixed
     */
    public static function getApp()
    {
        return static::$app;
    }

    /**
     * Get the repository as specified by the Entity class
     * @param mixed $entityClassName Fully Qualified Class Name
     * @return Repository
     */
    public static function getRepository($entityClassName)
    {
        $app = static::getApp();
        $entityMapper = $app->make('\EntityMapper\EntityMapper');

        // Determine if the entity declares a specific repository
        // TODO: This is called twice, and if not cached, it's a slow process. Code smell?
        // -- Also called in EntityMapper
        $table = $app->make('\EntityMapper\Cache\EntityCache')->get($entityClassName);

        // Set repository and its dependencies
        $repository = ($table->repository() === 'base') ? new static : $app->make($table->repository());
        $repository->setEntity($entityClassName);
        $repository->setMapper($entityMapper);

        return $repository;
    }

    /**
     * Handle dynamic method calls into the method.
     * Pass them to a new query Builder object
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $query = $this->query();

        return call_user_func_array(array($query, $method), $parameters);
    }
} 