<?php  namespace EntityMapper\Reflector; 

use Illuminate\Support\Collection;

class ColumnCollection extends Collection {

    /**
     * Get all ID columns defined
     * TODO: Get "auto"/"generated" id column separately?
     * @return ColumnCollection
     */
    public function idColumns()
    {
        $ids = [];
        foreach( $this->items as $column )
        {
            if( $column->isId() )
            {
                $ids[] = $column;
            }
        }

        return new static($ids);
    }

    public function column($key)
    {
        return $this->get($key, null);
    }

    public function addColumn($key, Column $column)
    {
        $this->put($key, $column);
    }
}