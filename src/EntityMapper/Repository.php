<?php  namespace EntityMapper; 

class Repository {

    protected $table;

    public function find($id)
    {
        // Query
        // And hydrate from result
    }

    public static function __callStatic($name, $arguments)
    {
        //if getXRepository, parse "X" and return repository (static) for that model class
    }
} 