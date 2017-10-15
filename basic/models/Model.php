<?php

namespace Basic\Models;

use Basic\App;

class Model{

    protected $db;
    protected $db_config;
    protected $table;
    protected $fields;

    protected static $instance = NULL;

    public function __construct(){
        $this->db_config = App::getInstance()->getContainer()->config['db'];
        $this->db = App::getInstance()->getContainer()['db'];
    }

    public static function getModel(){
        if(!isset(static::$instance)){
            static::$instance = new static;
            static::createTable(static::$instance->table,static::$instance->fields);
        } 
        return static::$instance;
    }


    protected static function createTable($table,$fields){

        $dbname = static::$instance->db_config['dbname'];
       $sql = "CREATE TABLE IF NOT EXISTS $dbname.$table(id MEDIUMINT NOT NULL AUTO_INCREMENT,";

       foreach($fields as $name => $type){
            $sql .= "$name $type,";
       }
       $sql .= "PRIMARY KEY (id));";
        try{
        static::$instance->db->exec($sql);
        }catch(PDOException $e){
            die($e->getMessage());
        }

    }

    public function create($model){
        $dbname = $this->db_config['dbname'];
        $sql = "INSERT INTO $dbname.$this->table(";

        $last = end($model);
        $values = [];
        foreach($model as $key => $value){
            $sql .= "$key";
            if($value != $last)
                $sql .=",";
            $values[] = $value;
        }
        $sql .= ") VALUES(";
        for($i=0;$i<count($model);$i++)
        {
            $sql .= " ?";
            if($i != count($model)-1)
                $sql .=",";
        }
        $sql .= ");";
        $stmt = $this->db->prepare($sql);
        try{
        $stmt->execute($values);
        }catch(PDOException $e){
            return false;
        }
        return $this->db->lastInsertId();
    }


    public function all(){
        $sql = "SELECT * FROM $this->table";
        $stmt = $this->db->query($sql);
        return $stmt->fetchALl(PDO::FETCH_ASSOC);
    }
}