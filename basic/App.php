<?php

namespace Basic;

class App{

    protected $container;
    protected $router;
    public function __construct(){
        $this->container = new Container([
            'router' => function(){
                return new Router;
            }
        ]);
        $this->router = $this->container->router;
    }

    public function getContainer(){
        return $this->container;
    }

    public function configure($mode){
        switch($mode){
            case 'development' : $this->container['config'] =  function(){
                                    return json_decode(
                                        file_get_contents('config/development.json', FILE_USE_INCLUDE_PATH),
                                        true
                                        );
                                };
                                break;
            case 'production'  :  $this->container['config'] =  function(){
                                    return json_decode(
                                        file_get_contents('config/production.json', FILE_USE_INCLUDE_PATH),
                                        true
                                        );
                                };
                                break;
            default : $this->break('app incorrectly configured!');

        }
    }

    public function get($uri,$handler){
        if(is_array($handler)){
            if(count($handler) === 2){
                if($this->container->has($handler[0])){
                    $tmp = function() use($handler){
                        $obj = $this->container[$handler[0]];
                        return $obj->{$handler[1]}();
                    };
                    $handler = $tmp;
                }
            }
        }
        $this->router->addRoute($uri,$handler);
    }

    public function post($uri,$handler){
                if(is_array($handler)){
            if(count($handler) === 2){
                if($this->container->has($handler[0])){
                    $tmp = function() use($handler){
                        $obj = $this->container[$handler[0]];
                        return $obj->{$handler[1]}();
                    };
                    $handler = $tmp;
                }
            }
        }
        $this->router->addRoute($uri,$handler,'POST');
    }

    public function put($uri,$handler){
                if(is_array($handler)){
            if(count($handler) === 2){
                if($this->container->has($handler[0])){
                    $tmp = function() use($handler){
                        $obj = $this->container[$handler[0]];
                        return $obj->{$handler[1]}();
                    };
                    $handler = $tmp;
                }
            }
        }
        $this->router->addRoute($uri,$handler,'PUT');
    }
    
    public function delete($uri,$handler){
                if(is_array($handler)){
            if(count($handler) === 2){
                if($this->container->has($handler[0])){
                    $tmp = function() use($handler){
                        $obj = $this->container[$handler[0]];
                        return $obj->{$handler[1]}();
                    };
                    $handler = $tmp;
                }
            }
        }
        $this->router->addRoute($uri,$handler,'DELETE');
    }

    public function route($uri,$class){
        if($this->container->has($class)){
            $object = $this->container[$class];
            $this->get($uri,function()use($object){
                return $object->show();
            });
            $this->post($uri,function()use($object){
                return $object->store();
            });
            $this->put($uri,function()use($object){
                return $object->update();
            });
            $this->delete($uri,function()use($object){
                return $object->remove();
            });
        }
    }

    public function run(){
        $this->router->setPath($_SERVER['PATH_INFO'] ?? '/');
        $response = $this->router->getResponse();
        $this->process($response);
    }

    protected function process($callback){
        return $callback();
    }

    public function break($msg){
        die($msg);
    }

}