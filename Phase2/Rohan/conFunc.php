<?php
function openConnection(){
    $serverName = "localhost";
    $connectionOptions = array("Database"=>"db2");
    $conn = mysqli_connect($serverName,"root","");
    if ($conn == false) {
        session_destroy();
    }
        return $conn;
}
?>