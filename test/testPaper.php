<?php 
if(!isset($_POST['testId'])){
    header('location:../index.php');
}
require('../connection.php');


$testId = testInput($_POST['testId']);
$class = testInput($_POST['class']);
$section = testInput($_POST['section']);

$table ="test_".$class;
$tableName = "result_".$class."@".$testId;


$stmt = $conn->prepare("select `question`,`testStartingTime`,`testEnd`,`testName` from `$table` where id=?");
$stmt->execute([$testId]);
$testInformation = $stmt->fetch(PDO::FETCH_NUM);

$data = explode('@#newQuestion',$testInformation[0]);
$questionNumber = 1;
    $readable = '
    <form id="test" action="submitTest.php" method="post">
                <div class="alerter">

                    <div class="warmTilte">
                        <p>Are you sure you want to submit this test!</p>
                    </div>
                    <div class="warmOption">
                        <input type="button" onclick="cancel()" value="Cancel">
                        <button type="submit">Yes</button>
                    </div>
                </div>';
    $fullmarks = 0;
    foreach($data as $splitedData){

        $splitedData = explode('@#t',$splitedData);
        $optionNumber = 0;
        $fullmarks += (int)$splitedData[2];
        $final= "<div class='questionBox' id='$questionNumber'>  <div id='info-question'><div class='question-number'>Q$questionNumber </div><div class='marks'>Marks :- ".$splitedData[2]."</div></div>";
        $quesitonType = $splitedData[1];
        if($quesitonType == 'oneValid'){
            $final = $final."<div class='answer'>  <p id='question'>".$splitedData[0]."</p>";
            foreach(explode(',',$splitedData[3]) as $option){
                $final = $final."<div class='radio'><input type='radio' id='questionNumber".$questionNumber."_$optionNumber' name='questionNumber".$questionNumber."_0' value='$option' class='check-box' ><label for='questionNumber".$questionNumber."_$optionNumber' class='radio-label'>$option</label></div>";
                $optionNumber++;
            }
            $final = $final."<input type='hidden' name='optionOn_$questionNumber' value='$optionNumber'></div></div>";
        }
        elseif($quesitonType == 'mulitiValid'){
            $final = $final."<p id='question'>".$splitedData[0]."</p><div class='answer'>";
            foreach(explode(',',$splitedData[3]) as $option){
                $final = $final."<div class='option'><input type='checkbox' name='questionNumber".$questionNumber."_$optionNumber' value='$option' id='questionNumber".$questionNumber."_$optionNumber'><label for='questionNumber".$questionNumber."_$optionNumber' class='check-box-lable'>$option</label></div>";
                $optionNumber++;
            }
            $final = $final."<input type='hidden' name='optionOn_$questionNumber' value='$optionNumber'></div></div>";
        }

        elseif($quesitonType == 'blank'){
            while(strpos($splitedData[0],'@blank')){
                $Replacement = '<input type="text" placeholder="Anwser here" autocomplete="off" name="questionNumber'.$questionNumber.'_'.$optionNumber.'" id="questionNumber'.$questionNumber.'_'.$optionNumber.'">';
                $splitedData[0] = preg_replace('/@blank/', $Replacement, $splitedData[0], 1);
                $optionNumber++;
            }

        $final = $final."<p id='question'>".$splitedData[0]."</p><input type='hidden' name='optionOn_$questionNumber' value='$optionNumber'></div>";
        }
        else {
            $final = $final."<p id='question'>".$splitedData[0]."</p><textarea  id='questionNumber".$questionNumber."_$optionNumber' name='questionNumber".$questionNumber."_$optionNumber' placeholder='Answer here'></textarea><input type='hidden' name='optionOn_$questionNumber' value='$optionNumber'></div>";
            
        }
        $readable = $readable.$final;
    $questionNumber++;
    
    }
    echo $readable.'<input type="hidden" name="testId" value="'.$testId.'">
                        <input type="hidden" name="class" value="class_'.$class.$section.'">
                        <input type="hidden" name="fullMarks" value="'.$fullmarks.'">
                        <input type="hidden" name="totalQuestion" value="'.$questionNumber.'">
                        </form>';

?>