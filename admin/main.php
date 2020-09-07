<?php 
if(!isset($_GET['bypass'])){
    header('location:../index.php');
}


if(!password_verify('success', $_GET['bypass'])){
    header('location:../index.php');
}

session_start();
if(!isset($_SESSION['level'])){
    header('location:../index.php');
}
echo "<a href='logout.php'>Log Out</a><br>";


$class = $_GET['class'];
require('../connection.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="icon" href="../assert/favico.png" type="image/png" sizes="16x16">
</head>
<body>
<h1>Selected Class is <?php echo $class;?></h1>
<a href="student.php?class=<?php echo $class;?>&bypass=$2y$10$bBtdlEP84gJdhfkkhTd1xevlRK.r9q4MgCkGl64TUgoMT02tbUvTi">Manage student</a><br>
<a href="test.php?class=<?php echo $class;?>&bypass=$2y$10$bBtdlEP84gJdhfkkhTd1xevlRK.r9q4MgCkGl64TUgoMT02tbUvTi">Add test</a><br>
<a href="result.php?class=<?php echo $class;?>&bypass=$2y$10$bBtdlEP84gJdhfkkhTd1xevlRK.r9q4MgCkGl64TUgoMT02tbUvTi">View result</a><br>
</body>
</html>