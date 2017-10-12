<?php

require 'vendor/autoload.php';

$app = new Basic\App();

$container = $app->getContainer();

$app->configure('development');  

$container['db'] = function($container){
    $db_config = $container->config['db'];
    return new PDO(
        $db_config['driver'].':host='.$db_config['host'].';dbname='.$db_config['dbname'],
        $db_config['user'],
        $db_config['pass']
    );
};

$app->get('/',function($response){
    return $response->setBody('Home')->withStatus(200);
});

$app->get('/test',[Basic\Controller\TestController::class,'index']);

$app->route('/users','Basic\\Controller\\UserController');

$app->run();