<?php
$host="localhost";
$password="";
$dbname="fashion";
$username="root";

$conn=new mysqli("localhost","root","","fashion");
if(!$conn){
    die("connection failed".mysqli_connect_error());
}

?>