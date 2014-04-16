<?php  namespace EntityMapper\Reflector; 

use Illuminate\Support\Collection;

class PropertyCollection extends Collection {

    /**
     * Get the Primary ID column
     * @return PropertyCollection
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
        return $this->get($key, null);
    }

    public function addProperty($key, Property $property)
    {
        $this->put($key, $property);
    }
}