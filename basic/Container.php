<?php

namespace Basic;

use ArrayAccess;

class Container implements ArrayAccess{

    protected $items = [];
    protected $cache = [];

    public function offsetSet($offset,$value){
        $this->items[$offset] = $value;
    }

    public function offsetGet($offset){
        if(!$this->has($offset)){
            return null;
        }
        if(isset($cache[$offset])){
            return $cache[$offset];
        }
        $item = $this->items[$offset]($this);
        $cache[$offset] = $item;
        return $item;

    }

    public function offsetUnset($offset){
        if($this->has($offset)){
            unset($this->items[$offset]);
        }
    }

    public function offsetExists($offset){
        return isset($this->items[$offset]);
    }

    public function has($offset){
        return $this->offsetExists($offset);
    }

    public function __get($property){
        return $this->offsetGet($property);
    }
    
}