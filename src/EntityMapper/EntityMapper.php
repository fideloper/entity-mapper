<?php  namespace EntityMapper;

use EntityMapper\Reflector\Entity;
use EntityMapper\Reflector\Relation;
use EntityMapper\ValueObjectInterface;
use EntityMapper\Cache\EntityCacheInterface;
use Illuminate\Container\Container;

/**
 * Map/Hydrate an Entity
 * Class EntityMapper
 * @package EntityMapper
 */
class EntityMapper {

    /**
     * Cached entities so they don't need re-retrieval
     * from cache within a request if this same entity
     * mapper class is used
     * @var array
     */
    protected $cachedEntities = [];

    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * @var \EntityMapper\Cache\EntityCacheInterface;
     */
    private $entityCache;

    /**
     * Create a new EntityMapper
     * @param Container $container
     * @param \EntityMapper\Cache\EntityCacheInterface $entityCache
     */
    public function __construct(Container $container, EntityCacheInterface $entityCache)
    {
        $this->app = $container;
        $this->entityCache = $entityCache;
    }

    /**
     * @param mixed $class
     * @param Array $results
     * @return array
     */
    public function create($class, Array $results)
    {
        $entities = [];

        foreach( $results as $result )
        {
            $entities[] = $this->hydrate($class, (array)$result);
        }

        return $entities;
    }

    /**
     * @param mixed $class
     * @param array $result
     * @return object
     */
    public function hydrate($class, Array $result)
    {
        $entity = $this->entity($class);

        if( ! is_object($class) )
        {
            $class = $entity->reflector()->newInstanceWithoutConstructor();
        }

        $properties = $entity->properties();
        $methods = $entity->methods();
        $reflector = $entity->reflector();

        foreach( $result as $column => $value )
        {
            $property = $properties->column($column);

            $reflectedProperty = $reflector->getProperty( $property->property() );

            // If it's a Value Object, use the database
            // value to create the class
            if( $property->isValueObject() )
            {
                $value = $this->app->make( $property->type(), [$value]);
            }

            // Check if there's a method assigned
            // as a setter for this property
            $usedSetter = false;
            if( $setter = $methods->setter( $property->property() ) )
            {
                $method = $reflector->getMethod( $setter->method() );

                if( $method->isPublic() )
                {
                    call_user_func(array($class, $method->getShortName()), $value);
                    $usedSetter = true;
                }
            }

            // Finally, if property was not set via a method
            // set it manually on the property
            if( $usedSetter === false )
            {
                $reflectedProperty->setAccessible(true);
                $reflectedProperty->setValue(
                    $class,
                    $value
                );
            }
        }

        return $class;
    }

    /**
     * Get array of data from object
     * @param $object
     * @throws \DomainException
     * @return array
     */
    public function dehydrate($object)
    {
        $entity = $this->entity($object);

        $properties = $entity->properties();
        $reflector = $entity->reflector();
        $methods = $entity->methods();

        $data = [];
        foreach( $properties as $property )
        {
            if( $property instanceof Relation && ! $property->isColumn() )
            {
                continue;
            }

            // Value starts at null
            $value = null;

            // If it has a public getter, use that
            $getterUsed = false;
            if( $getter = $methods->getter( $property->property() ) )
            {
                $method = $reflector->getMethod( $getter->method() );

                if( $method->isPublic() )
                {
                    $value = call_user_func(array($object, $method->getShortName()));
                    $getterUsed = true;
                }
            }

            // If value is still null because there's no setter, let's get it directly
            // However, if a getter was used, skip this. The value can still be null
            // as a result of a getter (i.e. in the case of an ID not yet set)
            if( is_null($value) && ! $getterUsed )
            {
                $reflectedProperty = $reflector->getProperty( $property->property() );
                $reflectedProperty->setAccessible( true );
                $value = $reflectedProperty->getValue( $object );
            }

            // If it's an object, there's some
            // work to do to make it DB-friendly
            if( is_object($value) )
            {
                // This is the easiest way out, use the __toDb()
                // method. This could end up being THE way to persist
                // Value Objects for now...
                if( $value instanceof ValueObjectInterface )
                {
                    $value = $value->__toDb();
                } else {
                    // Users need to implement ValueObjectInterface on their value objects
                    // This sucks for users who need value objects with multiple columns of data
                    // like users with an address table. For now, they can use relationships.
                    throw new \DomainException('Value objects must implement EntityMapper\ValueObjectInterface');
                }
            }
            // TODO: Investigate where DateTimes are converted properly for database

            $data[$property->column()] = $value;
        }

        return $data;
    }

    /**
     * Retrieve entity from class or class name
     * @param $class
     * @return \EntityMapper\Reflector\Entity
     */
    protected function entity($class)
    {
        if( ! is_string($class) )
        {
            $class = get_class($class);
        }

        if( ! isset($this->cachedEntities[$class]) )
        {
            $this->cachedEntities[$class] = $this->entityCache->get($class);
        }

        return $this->cachedEntities[$class];
    }

    /**
     * Get the repository class mapped
     * to an entity
     * @param $class
     * @return string
     */
    public function repository($class)
    {
        return $this->entity($class)->repository();
    }

    /**
     * Return the table name mapped
     * to an entity
     * @param $class
     * @return string
     */
    public function table($class)
    {
        return $this->entity($class)->table();
    }

    /**
     * Get ID column name mapped
     * to an entity
     * @param $class
     * @return string
     */
    public function idColumn($class)
    {
        return $this->entity($class)->properties()->idProperty()->column();
    }

} 