<?php 

if(!isset($_POST['searched'])){
    header('location:../../index.php');
}

require('../../connection.php');

$name = "%".$_POST['searched']."%";
$class = $_POST['class'];
$stmt = $conn->prepare("SELECT `rollno`, `name`, `password` FROM `$class` WHERE `name` LIKE ?");
$stmt->execute([$name]);
while($result = $stmt->fetch(PDO::FETCH_NUM)){
    echo "<tr><td>".$result[0]."</td><td>".$result[1]."</td><td>".$result[2]."<td></tr>";
}

?>