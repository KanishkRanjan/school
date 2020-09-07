<?php 

if(!isset($_POST['class'])){
    header('location:../../index.php');
}

require('../../connection.php');

$class = testInput($_POST['class']);
$testName = testInput($_POST['testName']);
$testStartingDate = testInput($_POST['testStartingDate']);
$testStartingTime = testInput($_POST['testStartingTime']);
$testEnd = testInput($_POST['testEnd']);
$question = $_POST['question'];
$tb = 'test_'.$class;
$conn->prepare("INSERT INTO $tb (`id`, `testName`, `testStartingDate`, `testStartingTime`, `testEnd`, `question`) VALUES (NULL, ? , ? , ? , ? , ? );")->execute([$testName,$testStartingDate,$testStartingTime,$testEnd,$question]);
$stmt = $conn->query("select `id` from `$tb` where testName='$testName'");

$id = $stmt->fetch(PDO::FETCH_NUM)[0];
$conn->query("CREATE TABLE `result_$class@$id` ( `id` INT NOT NULL AUTO_INCREMENT ,  `rollno` INT NOT NULL ,   `answer` TEXT NOT NULL ,  `section` CHAR(32) NOT NULL ,    PRIMARY KEY  (`id`));");

echo "process was secessful and shareable like is : $server/testKa/login.php?class=$class&testId=$id";

?>