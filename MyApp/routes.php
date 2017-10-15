<?php

$app->get('/',function($response){
    return $response->setBody('Home')->withStatus(200);
});

$app->get('/test',[Basic\Controller\TestController::class,'index']);

$app->route('/users','Basic\\Controller\\UserController');