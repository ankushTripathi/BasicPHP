<?php

namespace Basic\Controller;


abstract class Controller{

    abstract public function show();
    abstract public function store();
    abstract public function remove();
    abstract public function update();
    
}