<?php
date_default_timezone_set("Asia/Kolkata");

try{
    $server = "localhost";
    $userName = "root";
    $passwd = "";
    $dbname ="testKa";

    $conn = new PDO("mysql:host=$server;dbname=$dbname",$userName,$passwd);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

} catch(PDOException $e){  
    die("Problem to connect with db");
}


function testInput($data){
    try{
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } catch(PDOException $e){  
        die("Problem to connect with db check".$e);
    }

}
?>