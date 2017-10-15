<?php

namespace Basic\Models;

class User extends Model{

    protected $table = 'users';

    protected $fields = [
        'username' => 'varchar(255)',
        'email' => 'varchar(255)',
        'password' => 'varchar(255)',
        'first_name' => 'varchar(255)',
        'last_name' => 'varchar(255)',
    ];

}