<?php

namespace Basic\Controller;

use Basic\App;

abstract class Controller{

    protected $container;

    public function __construct(){
        $this->container = App::getInstance()->getContainer();
    }
    
}