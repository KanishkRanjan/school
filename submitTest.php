<?php 
session_start(); 

require('connection.php');

if(!$_SESSION["rollno"]){
    header('location:index.php');
}
$questionNumber = 1;
$final = [];
$rollno = $_SESSION["rollno"];
$class = $_SESSION["class"];
$testId = $_SESSION["testId"];
$section = $_SESSION['section'];

$currentTime = $datetime->format('H:i');


function minutes($timeTaken){
    $timeTaken = explode(':', $timeTaken);
    return (int)($timeTaken[0]*60) + $timeTaken[1] ;
  }
  
$startingTimem  = minutes($startingTime);
$endingTimem = minutes($endingTime); 


if($timeGiven <= 0){
    die("This test got ended at $endingTime");
} else {
    try{
        if($_SERVER["REQUEST_METHOD"] =="POST"){
            $totalQuestion = (int)testInput($_POST['totalQuestion']);
            while (true){
                $optionNumber = 0;
                $questionIndex = $questionNumber-1;
                if(!isset($_POST["questionNumber".$questionNumber."_0"])){
                    array_push($final,str_replace("@#a","a ","Not answered"));
                } else {
                    $answer = str_replace("@#ao","ao",testInput($_POST["questionNumber".$questionNumber."_0"]));
                    if($answer == ''){
                        $answer = "Not Answered";
                    }
                    array_push($final,str_replace("@#a","a ",str_replace("@#a","a",$answer)));
                }
                $totalOption = testInput($_POST["optionOn_$questionNumber"]);
                while(true){
                    if($totalOption == $optionNumber){
                        break;
                    }
                    $optionNumber++;
                    if(isset($_POST["questionNumber".$questionNumber."_$optionNumber"])){
                        $answer = testInput($_POST["questionNumber".$questionNumber."_$optionNumber"]);
                        if($final[$questionIndex] == "Not Answered"){
                            if($answer != "Not Answered") {
                                $final[$questionIndex] = $final[$questionIndex].str_replace("@#a","a",$answer);
                            }
                        } else {
                            $final[$questionIndex] = $final[$questionIndex].'@#ao'.str_replace("@#ao","ao",$answer);
                        }
                    } 
                }
                $questionNumber++;
                if($questionNumber == $totalQuestion){
                    break;
                }
            }
    
            $final = join("@#a",$final);
    
            $tableName = 'result_'.$class.'@'.$testId;
            $stmt = $conn->prepare("insert into `$tableName`(`id`, `rollno`, `answer`, `section`) values(NULL,?,?,?)");
            $stmt->execute([$rollno,$final,$section]);
    
            unset($_SESSION['level']);
            unset($_SESSION["rollno"]);
            unset($_SESSION["class"]);
            unset($_SESSION["class"]);
            unset($_SESSION["testId"]);
            unset($_SESSION['section']);
            session_destroy();
    
            header('location:../index.php')
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Thank you!</title>
                <link rel="icon" href="assert/favico.png" type="image/png" sizes="16x16">
                <style>
                    @font-face {
                        font-family: Ubuntu-Bold;
                        src: url(assert/Ubuntu-B.ttf);
                    }
                    img{
                        width:99vw;
                        height:90vh;
                    }
                    .title{
                        margin:0 auto;
                        font-family: Ubuntu-Bold;
                        color:#6699ff;
                    }
                    body{
                        overflow:hidden;
                    }
                </style>
            </head> 
            <body>
               <div class="title" style="text-align:center;margin:50px;">
                    <h2>Your test is successfuly submit</h2>
                </div>
                <div class="image">
                    <image src="assert/done.jpg" alt="DOne" >
                </div>
            </body>
            </html>';
        } else {
            die("Failed to sumbit test");
        }
    }catch(PDOException $e){  
        die("Problem to connect with db check".$e);
    }
    
}
?>
