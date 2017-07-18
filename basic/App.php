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
        $this->router->addRoute($uri,$handler);
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