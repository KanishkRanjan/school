<?php 
if(!isset($_POST['grade'])){
    header('location:../../index.php');
}

require('../../connection.php');
$grade = testInput($_POST['grade']);
$search = testInput($_POST['search']);
$theMainProblem = "test_$grade";

$class = testInput($_POST['class']);
$stmt=$conn->query("select `id`,`testName`,`testStartingDate` from `$theMainProblem` where testName='$search'");
// $stmt = $conn->prepare("select `id`,`testName`,`testStartingDate` from `:name` where testName=':search'")->execute(["name" => $theMainProblem,"search" => $search]);

while($result = $stmt->fetch(PDO::FETCH_NUM)){
?>
    <tr>
        <td><?php echo $result[0];?></td>
        <td><?php echo $result[1];?></td>
        <td><?php echo $result[2];?></td>
        <td><a href="result/detail.php?class=<?php echo $class;?>&testId=<?php echo $result[0];?>&bypass=$2y$10$bBtdlEP84gJdhfkkhTd1xevlRK.r9q4MgCkGl64TUgoMT02tbUvTi">View detail</a></td>
    </tr>    
<?php
    }
?>