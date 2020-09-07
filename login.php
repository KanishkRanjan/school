<?php 


if(!isset($_GET['class']) || !isset($_GET['testId']) || $_GET['testId'] == "" || $_GET['class']==""){
    header('location:index.php');
}
require('connection.php');

$error = $userName = "";

$class = testInput($_GET['class']);
$testId = testInput($_GET['testId']);
if(isset($_POST['submit'])){
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $section = testInput(strtolower($_POST['section']));
        $name = testInput($_POST['name']);
        if(strlen($class) != 1 || strlen($section) != 1){
            $error = "Incorrect class or section filled";
        } else {
            $tableName = 'class_'.$class."$section@student";
            $enterPassword = testInput($_POST['password']);
            $stmt = $conn->prepare("select `rollno`,`name`,`password` from `$tableName` where `rollno` = ?");
            $stmt->execute([$name]);
            $result = $stmt->fetch(PDO::FETCH_NUM);
            if($result != ''){
                if($result[2] == $enterPassword){   
                    session_start();               
                    $_SESSION["rollno"] = $result[0];
                    $_SESSION["class"] = $class;
                    $_SESSION["testId"] = $testId;     
                    $_SESSION['section'] = $section;
                    $_SESSION["login_time_stamp"] = time();   
                    header("Location:test.php"); 
                } else {
                    $error = $error."Incorrect username or password";
                    $userName = $name;
                }
            } else {
                $error = $error."Incorrect username or password";
                $userName = $name;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login -testka</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet"> 
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="icon" href="assert/favico.png" type="image/png" sizes="16x16">

</head>     
<body>
    <div class="content">
        
        <span style="color:red;text-align:center;"><?php echo $error;?></span>

        
        <p class="title">
            <img src="assert/Logo.png" alt="logo"><br>
            Class 9
        </p>
        <form action="login.php?class=<?php echo $class;?>&testId=<?php echo $testId;?>" method="post">

            <div class="form-name">
                <input type="text"  maxlength = "1" autocomplete="off" name="name" id="name" placeholder="Roll number" />
                <input type="text" maxlength = "1" autocomplete="off" name="section" id="section" placeholder="Section" />
            </div>
            <div class="form-name">
                <input type="password" name="password" id="password" placeholder="Password"/>
            </div>
            <div class="form-name">
                <input type="submit" name="submit" id="submit" value="Sign Up">
            </div>
        </form>
        <div class="ad">
            <p class="title">Made by <br>Kanishk Ranjan</p>
        </div>
    </div>
</body>
</html>