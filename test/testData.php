<?php 
if(!isset($_POST['class'])){
    header('location:../index.php');
}

require('../connection.php');

$name = testInput($_POST['name']);
$password = testInput($_POST['password']);
$class = testInput($_POST['class']);
$section = testInput($_POST['section']);


$error = '';

$tableName = 'class_'.$class."$section@student";

if(strlen($class) == 1){
    $stmt = $conn->prepare("select `rollno`,`name`,`password` from `$tableName` where `name`=? or `rollno` = ?");
    $stmt->execute([$name,$name]);
    $result = $stmt->fetch(PDO::FETCH_NUM);
    if($result != ''){
        if($result[2] == $password){     
            echo "success";
        }
    } else {
        $error = "Incorrect username or password";
        }
} else {
    $error = "Invalid Url";
}

echo $error;
?>