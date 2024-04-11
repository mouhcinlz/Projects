<?php

$host = "localhost";
$username = "root";
$password = "";
$db = "farmvet";

$data = mysqli_connect($host,$username,$password,$db);

if ($data===false){
    die("connection error");
}


$firstn = "jawed";
$lastn = "mkl";

$query = "insert into farmer (firstn,lastn) values ('$firstn','$lastn')";

mysqli_query($data,$query);




