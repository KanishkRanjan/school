<?php 

if(!isset($_POST['class'])){
    header('location:../../../index.php');
}
require('../../../connection.php');

$class = testInput($_POST['class']);
$testId = testInput($_POST['testId']);
$studentName  = testInput($_POST['searched']);
$section = $class[-1];
$grade = $class[-2];
$tb = "result_$grade@$testId";
$attemptedStudents = $conn->query("select  `rollno`, `answer`, `section` from `$tb`  where `section` = '$section';");
$students = $conn->query("select `rollno`,`name` from `$class@student` where `name` like '%$studentName%'");


if($attemptedStudents){
    $attemptedStudents = $attemptedStudents->fetchAll(PDO::FETCH_NUM);
}
$students = $students->fetchAll(PDO::FETCH_NUM);


foreach($students as $student){
    echo "<tr id='rollNo_".$student[0]."'>";
    if($attemptedStudents){
        foreach($attemptedStudents as $attemptedStudent){
            $answerStudent = '';
            if($attemptedStudent[0] == $student[0]){
                $status = 'attempted';
                $answerStudent = $attemptedStudent[1];
            break;
            } else {
                $status = 'Unattempted';
            }
        }  
    } else {
        $status = 'Unattempted';
        $answerStudent = '';
    }
      
    echo "<input type='hidden' value='".$answerStudent."'>";
    echo "<td>".$student[0]."</td>";
    echo "<td id='name'>".$student[1]."</td>";
    echo "<td>$status</td>";  
    echo "</tr>";
}

?>