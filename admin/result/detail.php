<?php 

if(!isset($_GET['bypass'])){
    header('location:../../index.php');
}


if(!password_verify('success', $_GET['bypass'])){
    header('location:../../index.php');
}

session_start();
if(!isset($_SESSION['level'])){
    header('location:../../index.php');
}
echo "<a href='../logout.php'>Log Out</a><br>";
require('../../connection.php');

$testId = testInput($_GET['testId']);
$class = testInput($_GET['class']);
$tb = "test_".$class[-2];
$question = $conn->prepare("SELECT `testName`,`testStartingDate`,`question` FROM `$tb` where `id`=:testId");
$question->execute(array(':testId' => $testId));
$question = $question->fetch(PDO::FETCH_NUM);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail on test Id :-<?php echo $testId;?></title>
    <link rel="icon" href="../../assert/favico.png" type="image/png" sizes="16x16">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .studentAnswer{
            width:100%;
            height:100%;
            background-color:#dddddd;
            position:fixed;
            top:0; 
            display:none;
        }

        .close{
            margin :5px;
            padding:5px;
        }

        .result
        {
            margin:10px;
            padding:10px;
        }
    </style>
</head>
<body>
        
    <div class="information">
        <H1>View detail result of test name:-<?php echo $question[0];?></H1>
        <p>Test Data :-<?php echo $question[1];?><br></p>
        
    </div>

    <div class="search">
        <div class="function">
            <label for="student">Seached Student</label><br>
            <input type="text" name="student" id="student" placeholder="Search Student"><p></p>

            <div class="searchResult"></div><p></p>
        </div>
        <div class="result"></div>
    </div>
    <div class="studentAnswer">
        <div class="main">
            <div class="close">
                <p>Close</p>
            </div>
            <h2 style="text-align: center; color:#333;"><span id="studentName"></span></h2>
            <p style="text-align: center; color:#333; font-size:26px;"><span id="studentRollno"></span></p>
            <h3 style="text-align: center; color:#333;">Student Answer</h3>
        </div>
        <div class="data"></div>
    </div>
</body>
<script>
    var oldData = '';
    var question = '<?php echo $question[2];?>';
    function getData(){
        $.ajax({
            url:'detail/getResult.php',
            type:'post',
            data:{'class':'<?php echo $class;?>','testId':'<?php echo $testId;?>'},
            success:function(result){
                if(result == ''){
                    result = "<h1>Error while getting data from server! please report Kanishk </h1>";
                } else {
                    result = "<table><th>rollno</th><th>Name</th><th>Status</th>"+result+"</table>";
                    if(result != oldData){
                        oldData = result;
                        $('.result').html(result);
                    }   
                    showAnswer();
                }
            }
        })
    }

    setInterval(getData,30000);


    $('#student').keyup(function(){
        var searched = $(this).val();
        if(searched == ''){
            $('.searchResult').html('');
        } else {    
            $.ajax({
                url:'detail/searchStudent.php',
                type:'post',
                data:{'class':'<?php echo $class;?>','testId':'<?php echo $testId;?>','searched':searched},
                success:function(result){
                    if(result == ''){
                        result = "<h1>Opps! No Data Found</h1>";
                        
                    } else {
                        result = "<table><h1>Search Result :- </h1><br><th>rollno</th><th>Name</th><th>Status</th>"+result+"</table>";
                    }
                    $('.searchResult').html(result);
                    showAnswer();
                }
            })
        }
    });

    function showAnswer(){
        $('tr').click(function(){
            var tableId = $(this).attr('id');
            var answer = $('#'+tableId).find('input').val();
            if(answer != ''){
                $('.studentAnswer').css('display','block');
                converter(answer,tableId);
            }
        })
    }

    $('.close').click(function(){
        $('.studentAnswer').css('display','none');
        $('#studentName').html('');
        $('#studentRollno').html('');
        $('.data').html('');
    })

    function converter(answer,studnetId){
        while(answer.includes('@#ao')){
            answer = answer.replace('@#ao',',<br>');
        }
        answer = answer.split('@#a');
        var functionalQuestion = question.split('@#newQuestion');
        var questionNumber = 1;
        var final = '';
        var studentRollno = studnetId.slice(-1);
        var studentName = $('#'+studnetId).find('#name').text();

        functionalQuestion.forEach(function(item, index, array){
            questionNumber++;
            item = item.split('@#t');
            var finalQuestion = item[0];
            var marks = item[2];
            final += "<div class='result'><p>question no:-"+questionNumber+"<br><b>"+finalQuestion+"</b><br>Mark alloted :-"+marks+"</p><p>"+answer[index]+"</p></div><hr>";
        });

        $('.data').html(final);
        $('#studentName').text(studentName);
        $('#studentRollno').html("Roll no :-"+studentRollno);
    } 

    showAnswer();
    getData();

</script>
</html>