<?php

class Database {

    private $host = "localhost";
    private $username = "root";
    private $passwords = "";
    private $db = "farmvet";

    function connect(){

        $datab = mysqli_connect($this->host,$this->username,$this->passwords,$this->db);
        return $datab;
    }

    function read($query){
        $con = $this->connect();
        $result = mysqli_query($con,$query);

        if(!$result){
            return false;
        }
        else{
            $data = false;
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            return $data;
        }
    }

    function save($query){
        $con = $this->connect();
        $result = mysqli_query($con,$query);

        if(!$result){
            return false;
        }
        else{
            return true;
        }
    }
}



?>