<?php

namespace Basic\Controller;

class TestController extends Controller{
    
    public function index($response){
        return $response->setBody("fuck off");
    }
}