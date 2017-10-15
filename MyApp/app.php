<?php

require 'vendor/autoload.php';
$app = Basic\App::getInstance();

$app->setPathToApp(__DIR__);

$container = $app->getContainer();

$app->configure('development');  

$container['db'] = function($container){
    $db_config = $container->config['db'];
    $db = new PDO(
        $db_config['driver'].':host='.$db_config['host'].';dbname='.$db_config['dbname'],
        $db_config['user'],
        $db_config['pass']
    );

    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    return $db;
};