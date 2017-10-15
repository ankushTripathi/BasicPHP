<?php

namespace Basic;

use Basic\Exceptions\RouteNotFound;
use Basic\Exceptions\MethodNotAllowed;

class App{

    protected static $instance = NULL;
    protected $container;
    protected $router;
    protected $app_path;

    public function __construct(){
        $this->container = new Container([
            'router' => function(){
                return new Router;
            },
            'controller' => function(){
                return new Controller\Controller;
            },
            'response' => function(){
                return new Response;
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


    public function setPathToApp($path){
        $this->app_path = $path;
    }

    public function configure($mode){
        switch($mode){
            case 'development' : $this->container['config'] =  function(){
                                    return json_decode(
                                        file_get_contents($this->app_path.'/config/development.json', FILE_USE_INCLUDE_PATH),
                                        true
                                        );
                                };
                                break;
            case 'production'  :  $this->container['config'] =  function(){
                                    return json_decode(
                                        file_get_contents($this->app_path.'/config/production.json', FILE_USE_INCLUDE_PATH),
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
            $response = $this->container['response'];
                $this->get($uri,function()use($object,$response){
                return $object->show($response);
                });
                $this->post($uri,function()use($object,$response){
                    return $object->store($response);
                });
                $this->put($uri,function()use($object,$response){
                    return $object->update($response);
                });
                $this->delete($uri,function()use($object,$response){
                    return $object->remove($response);
                });
    }

    public function run(){
        $this->router->setPath($_SERVER['PATH_INFO'] ?? '/');
        try{
            $response = $this->router->getResponse();
        }catch(RouteNotFound $e){
            $this->respond($this->container['response']->setBody('Route Not Found!')->withStatus(404));
            return;
        }catch(MethodNotAllowed $e){
            $this->respond($this->container['response']->setBody('Method Not Allowed!')->withStatus(405));
            return ;
        }

        $this->respond($this->process($response));

    }

    protected function process($callback){
        $response = $this->container->response;
        if(is_array($callback)){
            if(!is_object($callback[0])){
                $callback[0] = new $callback[0];
            }
            return call_user_func($callback,$response);
        }
        return $callback($response);
    }

    public function respond($response){
        if(!$response instanceof Response){
            echo $response;
            return;
        }

        header(sprintf(
            'HTTP/%s %s %s',
            1.1,
            $response->getStatusCode(),
            ''
        ));

        foreach($response->getHeaders() as $header){
            header($header[0].': '.$header[1]);
        }

        echo $response->getBody();
    }

    public function break($msg){
        die($msg);
    }

}