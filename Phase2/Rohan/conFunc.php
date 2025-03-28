<?php
function openConnection(){
    $serverName = "localhost";
    $connectionOptions = array("Database"=>"collegesystem");
    $conn = mysqli_connect($serverName,"root","");
    if ($conn == false) {
        echo "connection failed";
    }
    
    else {
        echo "connection succeeded";
        return $conn;
    }
}
?>