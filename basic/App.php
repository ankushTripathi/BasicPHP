<?php

namespace Basic;

use Basic\Exceptions\RouteNotFound;
use Basic\Exceptions\MethodNotAllowed;

class App{

    protected static $instance = NULL;
    protected $container;
    protected $router;
    public function __construct(){
        $this->container = new Container([
            'router' => function(){
                return new Router;
            },
            'controller' => function(){
                return new Controller\Controller;
            }
        ]);
        $this->router = $this->container->router;
    }

    public static function getInstance(){
        if(!isset(self::$instance))
            self::$instance = new self;
        
        return self::$instance;
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
        $this->router->addRoute($uri, $handler);
    }

    public function post($uri,$handler){
        $this->router->addRoute($uri,$handler,'POST');
    }

    public function put($uri,$handler){
        $this->router->addRoute($uri,$handler,'PUT');
    }
    
    public function delete($uri,$handler){
        $this->router->addRoute($uri,$handler,'DELETE');
    }

    public function route($uri,$class){
            $object = new $class();
            
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

    public function run(){
        $this->router->setPath($_SERVER['PATH_INFO'] ?? '/');
        try{
            $response = $this->router->getResponse();
        }catch(RouteNotFound $e){
            die('exception caught');
        }
        $this->process($response);
    }

    protected function process($callback){
        if(is_array($callback)){
            if(!is_object($callback[0])){
                $callback[0] = new $callback[0];
            }
            return call_user_func($callback);
        }
        return $callback();
    }

    public function break($msg){
        die($msg);
    }

}