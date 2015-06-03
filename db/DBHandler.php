<?php

define("DB_USER", "root");
define("DB_PASS", "i12DBu");

class DBHandler {

    private $conn;

    function __construct(){
        try{

            //mysql:host=gtapssnet.ipagemysql.com;dbname=quotes;charset=utf8
            $connectionStr = 'mysql:host=localhost;dbname=quotes;charset=utf8';
            $this->conn = new PDO($connectionStr, DB_USER, DB_PASS);

        }
        catch(PDOException $e){
            echo $e->getMessage() . "<br/>";
            die();
        }
    }

    function __destruct(){
        unset($this->conn);
    }

    public function query($sql, $params=null){
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        unset($stmt);
        return $results;
    }

}