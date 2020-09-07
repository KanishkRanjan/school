<?php
if(!isset($_POST['testName'])){
    header('location:../../index.php');
}
require('../../connection.php');

$class = testInput($_POST['grade']);

$testName = testInput($_POST['testName']);

$stmt = $conn->query("SELECT * FROM `test_$class` WHERE `testName` = '$testName'");
$result = $stmt->fetchAll();
if(sizeof($result) !=0){
    echo "yes";
}

?>