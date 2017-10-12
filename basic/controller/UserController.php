<?php

namespace Basic\Controller;

class UserController extends Controller{

    public function store($response){

    }

    public function show($response){
        return $response->withJson([
            'id' => 1,
            'name' => 'ankush',
            'active' => true
        ]);
    }

    public function update($response){

    }
    
    public function remove($response){

    }
}