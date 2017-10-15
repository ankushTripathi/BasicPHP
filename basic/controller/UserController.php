<?php

namespace Basic\Controller;

use Basic\Models\User;
use Basic\App;

class UserController extends Controller{

    public function store($response){
        $user = [
            'username' => 'ankush',
            'email' => 'ankush@gmail.com',
            'password' => 'password',
            'first_name' => 'ankush',
            'last_name' => 'tripathi'
        ];
        if(($id = User::getModel()->create($user)))
            return $response->withJson(["id" => $id]);
        return $response->withJson(["error" => "could not insert user"])->withStatus(404);
    }

    public function show($response){

        // $user = new User();
        //User::get()

        // $user = {};
        // $user->save();
        // User::getAll()
        // User::getById()
        // User::where()->remove()

        $user = User::getModel()->all();
        return $response->withJson($user);
    }

    public function update($response){

    }
    
    public function remove($response){

    }
}