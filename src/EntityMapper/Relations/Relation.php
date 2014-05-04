<?php  namespace EntityMapper\Relations; 

use EntityMapper\Builder;

abstract class Relation {

    /**
     * EntityMapper Query Builder
     * @var \EntityMapper\Builder
     */
    protected $query;

    // Parent Entity Object?

    // Related Entity Object?

    // Add Constraints? Based on...what? (Eager vs Lazy?)

    // Touch? Related Count?

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * Get EntityMapper query
     * @return \EntityMapper\Builder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get Illuminate query object
     * @return \Illuminate\Database\Query\Builder
     */
    public function getBaseQuery()
    {
        return $this->query->getQuery();
    }

    /**
     * Handle dynamic method calls to the relationship.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $result = call_user_func_array(array($this->query, $method), $parameters);

        if ($result === $this->query) return $this;

        return $result;
    }
} 