<?php  namespace EntityMapper\Reflector; 

use Illuminate\Support\Collection;

class PropertyCollection extends Collection {

    /**
     * Get the Primary ID column
     * @return Property
     */
    public function idProperty()
    {
        foreach( $this->items as $property )
        {
            if( $property->isId() )
            {
                return $property;
            }
        }

        // Null columns? Throw Exception?
        return null;
    }

    /**
     * Get the all ID columns
     * TODO: Get "auto"/"generated" id column separately from keys?
     * @return PropertyCollection
     */
    public function idProperties()
    {
        $ids = [];
        foreach( $this->items as $property )
        {
            if( $property->isId() )
            {
                $ids[] = $property;
            }
        }

        return new static($ids);
    }

    public function property($key)
    {
        return $this->get('property.'.$key);
    }

    public function column($key)
    {
        return $this->get('column.'.$key);
    }

    public function relation($key)
    {
        return $this->get('relation.'.$key);
    }

    public function relations()
    {
        $relations = [];

        foreach( $this->items as $key => $item )
        {
            if( strpos($key, 'relation') === 0 )
            {
                $relations[] = $item;
            }
        }

        return new static($relations);
    }

    public function addProperty(PropertyInterface $property)
    {
        if( $property instanceof Relation )
        {
            $this->put('relation.'.$property->property(), $property);

            // TODO: Don't allow relations to be columns as well.
            // For example, have user define "user_id" and "user"
            // as separate properties on an object (??) if they want
            // the ID [good DDD to have ID and object? Not really...)
            if( $property->isColumn() )
            {
                $this->put('column.'.$property->column(), $property);
            }
        }

        if( $property instanceof Property )
        {
            $this->put('property.'.$property->property(), $property);
            $this->put('column.'.$property->column(), $property);
        }
    }
}