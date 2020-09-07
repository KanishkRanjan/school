<?php 
if(!isset($_POST['name'])){
    header('location:../../index.php');
}
require('../../connection.php');

$name = $_POST['name'];
$class = $_POST['class'];
$rollno = $_POST['rollno'];
$password = $_POST['password'];

$sql = "INSERT INTO `$class` (`id` , `rollno`, `name`, `password`) VALUES (NULL,?,?,?)";

$conn->prepare($sql)->execute([$rollno,$name, $password]);
echo "Insert $name in class $class successfuly!";
?>

