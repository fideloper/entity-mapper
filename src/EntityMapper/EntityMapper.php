<?php  namespace EntityMapper;

use EntityMapper\Reflector\Entity;
use EntityMapper\ValueObjectInterface;
use Illuminate\Container\Container;

/**
 * Map/Hydrate an Entity
 * Class EntityMapper
 * @package EntityMapper
 */
class EntityMapper {

    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * Create a new EntityMapper
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->app = $container;
    }

    /**
     * @param Entity $entity
     * @param Array $results
     * @return array
     */
    public function create(Entity $entity, Array $results)
    {
        $entities = [];

        foreach( $results as $result )
        {
            $entities[] = $this->hydrate($entity, (array)$result, $entity->reflector()->newInstanceWithoutConstructor());
        }

        return $entities;
    }

    /**
     * @param Entity $entity
     * @param array $result
     * @param mixed $concreteClass
     * @return object
     */
    public function hydrate(Entity $entity, Array $result, $concreteClass)
    {
        $properties = $entity->properties();
        $methods = $entity->methods();
        $reflector = $entity->reflector();

        foreach( $result as $column => $value )
        {
            $property = $properties->column($column);

            $reflectedProperty = $reflector->getProperty( $property->variable() );

            // If it's a Value Object, use the database
            // value to create the class
            if( $property->isValueObject() )
            {
                $value = $this->app->make( $property->type(), [$value]);
            }

            // Check if there's a method assigned
            // as a setter for this property
            $usedSetter = false;
            if( $setter = $methods->setter( $property->variable() ) )
            {
                $method = $reflector->getMethod( $setter->method() );

                if( $method->isPublic() )
                {
                    call_user_func(array($concreteClass, $method->getShortName()), $value);
                    $usedSetter = true;
                }
            }

            // Finally, if property was not set via a method
            // set it manually on the property
            if( $usedSetter === false )
            {
                $reflectedProperty->setAccessible(true);
                $reflectedProperty->setValue(
                    $concreteClass,
                    $value
                );
            }
        }

        return $concreteClass;
    }

    /**
     * Get array of data from object
     * @param Entity $entity
     * @param $object
     * @throws \DomainException
     * @return array
     */
    public function dehydrate(Entity $entity, $object)
    {
        $properties = $entity->properties();
        $reflector = $entity->reflector();
        $methods = $entity->methods();

        $data = [];
        foreach( $properties as $property )
        {
            // Value starts at null
            $value = null;

            // If it has a public getter, use that
            $getterUsed = false;
            if( $getter = $methods->getter( $property->variable() ) )
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
                $reflectedProperty = $reflector->getProperty( $property->variable() );
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

            $data[$property->name()] = $value;
        }

        return $data;
    }

} 