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

    public function __construct($entityClassName, EntityMapper $mapper)
    {
        $this->entity = $entityClassName;
        $this->mapper = $mapper;
    }

    public function all() {}
    public function find($id, $columns = array('*')){}
    public function findMany($id, $columns = array('*')){}
    public function findOrFail($id, $columns = array('*')) {}

    public function save($entity) {}
    public function delete($entity) {}
    public function touch($entity) {}

    public function query() {} // Builder?

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

    public static function __callStatic($name, $arguments)
    {
        //if getXRepository, parse "X" and return repository (static) for that model class
    }

    public static function setApp(Container $app)
    {
        static::$app = $app;
    }

    public static function getApp()
    {
        return static::$app;
    }

    public static function getRepository($entityClassName)
    {
        $app = static::getApp();
        $entityMapper = $app->make('\EntityMapper\EntityMapper');
        $table = $app->make('\EntityMapper\Cache\EntityCache')->get($entityClassName);

        if( $table->repository !== 'base' )
        {
            return $app->make($table->repository, [$entityClassName, $entityMapper]);
        }

        return new static($entityClassName, $entityMapper);
    }
} 