<?php  namespace EntityMapper;

use EntityMapper\Reflector\Entity;
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
    public function hydrate(Entity $entity, Array $results)
    {
        $entities = [];

        foreach( $results as $result )
        {
            $entities[] = $this->map($entity, $result);
        }

        return $entities;
    }

    /**
     * @param Entity $entity
     * @param \stdClass $result
     * @return object
     */
    protected function map(Entity $entity, \stdClass $result)
    {
        $properties = $entity->properties();
        $methods = $entity->methods();
        $reflector = $entity->reflector();
        $concreteClass = $reflector->newInstanceWithoutConstructor();

        foreach( $properties as $property )
        {
            $reflectedProperty = $reflector->getProperty( $property->variable() );

            // Get the database result for this property
            $column = $property->name();
            $value = $result->$column;

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

} 