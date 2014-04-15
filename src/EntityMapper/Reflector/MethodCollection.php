<?php  namespace EntityMapper\Reflector; 

use Illuminate\Support\Collection;

class MethodCollection extends Collection {

    public function addGetter($key, GetterMethod $getter)
    {
        if( ! $this->has('getters') )
        {
            $this->put('getters', []);
        }

        $this->items['getters'][$key] = $getter;
    }

    public function addSetter($key, SetterMethod $setter)
    {
        if( ! $this->has('setters') )
        {
            $this->put('setters', []);
        }

        $this->items['setters'][$key] = $setter;
    }

    public function getter($variableName)
    {
        if( array_key_exists($variableName, $this->items['getters']) )
        {
            return $this->items['getters'][$variableName];
        }

        return null;
    }

    public function setter($variableName)
    {
        if( array_key_exists($variableName, $this->items['setters']) )
        {
            return $this->items['setters'][$variableName];
        }

        return null;
    }
}