<?php
require('../../connection.php');

function addTestTable($conn,$grade){
    $stmt = $conn->prepare("CREATE TABLE `test_$grade` ( `id` INT(225) NOT NULL AUTO_INCREMENT ,  `testName` VARCHAR(225) NOT NULL ,  `testStartingDate` DATE NOT NULL ,  `testStartingTime` VARCHAR(150) NOT NULL ,  `testEnd` VARCHAR(150) NOT NULL ,  `question` TEXT NOT NULL ,    PRIMARY KEY  (`id`));");
    $stmt->execute();
}

function checkTable($conn,$name){
    $result = $conn->query("show tables");
    $valid = 1;
    $test = 1;
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $class = explode('@',$row[0])[0];
        $testTable = 'test_'.$name[-2];
        if($class == $name){
            $valid = 0;
        } 
        if($row[0] == $testTable){
            $test = 0;
        } 
    }
    if($valid == 1){
        echo "Student Table was added successful";
        $stmt = $conn->prepare("CREATE TABLE `$name@student` ( `id` INT NOT NULL AUTO_INCREMENT, `rollno` INT NOT NULL,`name` VARCHAR(225) NOT NULL, `password` VARCHAR(225) NOT NULL, PRIMARY KEY (`id`));");
        $stmt->execute();
        if($test == 1){
            addTestTable($conn,$name[-2]);
            echo "\nTest table was added";
        } else {
            echo "\nTest table wasn't add because table alredy exist";
        }    
    } else {
        echo "Already exist\nNO! class was added";
    }

}

if(isset($_POST['className'])){
    $className = $_POST['className'];
    $tableName = 'class_'.$className;
    if(strlen($className) == 2){
        checkTable($conn,$tableName);   
    } else {
        echo "Invalid Class Give \nYou must add value like 9b";
    }
} else {
    header('location:../../index.php');
}

?>