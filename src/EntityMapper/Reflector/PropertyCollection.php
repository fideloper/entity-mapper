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

    public function addProperty(Property $property)
    {
        $this->put('property.'.$property->variable(), $property);
        $this->put('column.'.$property->name(), $property);
    }
}