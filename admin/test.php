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
$grade = $class[6];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add test for class <?php echo $class;?></title>
    <link rel="icon" href="../assert/favico.png" type="image/png" sizes="16x16">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        #question{
            width:90%;
            margin: 0 auto;
        }
        .add{
            margin-bottom:0;
        }
        .preview{
            height:500px;
            width:80%;
            background-color:#EDEDED;
            overflow:scroll;
            padding:25px;
            margin:10px;
            border-radius:10px;
        }
        #invalid{
            color:grey;
        }
        
    </style>
</head>
<body>
<h2>Making test for <?php echo $class;?></h2>
<div class="teacherControl">
    <p><b>TotalMarks:- <span id="totalMarks">Will calculated where</span></b></p>   

    <button onclick="publishTest()">Publish</button><br>

    <label for="testName">Enter test name</label><br>
    <input type="text" name="testName" id="testName" placeholder="Enter test name"  ><br>

    <label for="testStartingDate">Test Starting Data</label><br>
    <input type="date" name="testStartingDate" id="testStartingDate"  ><br>

    <label for="testStartingTime"> Test starting time</label><br>
    <input type="time" name="testStartingTime" id="testStartingTime" ><br>

    <label for="testEnd"> Test starting end</label><br>
    <input type="time" name="testEnd" id="testEnd" ><br>

</div>

    <div class="preview">
        <p><b>No question added yet!</b></p>
    </div>

    <div class="add">
        <label for="question">Question :-</label><br>
        <textarea name="question" id="question" cols="30" rows="10"></textarea>
        <p></p>
        <label for="questionType">Question Type</label>
        <select name="type" id="questionType">
            <option value="inValid" id="inValid" disabled selected>Select Question Type</option>
            <option value="short">Short answer question</option>
            <option value="oneValid">Tick the correct option - one valid</option>
            <option value="mulitiValid">Tick the correct option - mulitiple valid</option>
            <option value="blank">Fill in the blank</option>
        </select><p></p>

        <input type="number" name="marks" id="marks" placeholder="Full marks">
        <p></p>

        <label for="options"><span id="loptions"></span></label>
        <input type="hidden" name="options" id="options" placeholder="Eg:- apple,mango,bread,milk" require>
        <p></p>

        <input type="button" value="Add question" id="doneAddNow">
    </div>
</body>

<script>

var questionAdded = [];

function totalMarkCalc(marksAlloted){
    $('#totalMarks').html(marksAlloted);
}

function makeReadable(){
    var totalMarks = 0;
    var questionDisplay = [];
    var questionNumber = questionAdded.length;
    if( questionNumber == 0){
        $('.preview').html('<p><b>No question added yet!</b></p>')
    } else {
        var questionNumberDemo = 1;
        questionAdded.forEach(function(element){
            element = element.split('@#t');
            var givenMarks = parseInt(element[2]);
            totalMarks += givenMarks;
            var final = '<div class="question" id="'+questionNumberDemo+'"><p>question no.'+questionNumberDemo+'<br><b>'+element[0]+'</b></p><p> Marks '+givenMarks+'</p><button onclick="questionRemove('+questionNumberDemo+')">Remove question</button><br>';
            if(element[1] == 'oneValid'){
                element[3].split(',').forEach(function(item, index, array){
                    final +='<input type="radio" id="'+questionNumberDemo+'"><label for="'+questionNumberDemo+'">'+item+'</label><br>';       
                }); 
                final += '</div><hr>';
            }   
            else if(element[1] == 'mulitiValid'){
                var optionNumber = 1;
                element[3].split(',').forEach(function(item, index, array){
                    final +='<input type="checkbox" id="'+questionNumberDemo+'"><label for="'+questionNumberDemo+'">'+item+'</label><br>';        
                });
                final += '</div><hr>';
            }
            else if(element[1] == 'blank'){
                while(element[0].includes('@blank')){
                    element[0] = element[0].replace('@blank','<input type="text" placeholder="______________________________________________" >');
                }
                final = '<div class="question" id="'+questionNumberDemo+'"><p>question no.'+questionNumberDemo+'<br><b>'+element[0]+'</b></p><p> Marks '+element[2]+'<br><button onclick="questionRemove('+questionNumberDemo+')">Remove question</button></p></div><hr>';
            }
            else{
                final = '<div class="question" id="'+questionNumberDemo+'"><p>question no.'+questionNumberDemo+'<br><b>'+element[0]+'</b></p><p> Marks '+element[2]+'<br><button onclick="questionRemove('+questionNumberDemo+')">Remove question</button></p><textarea></textarea></div><hr>';
            }
            questionDisplay.push(final);
            questionNumberDemo++;
            questionConverter(questionDisplay);
        });
    }
    totalMarkCalc(totalMarks);
}

function questionRemove(id){
    id -=1;
    var selectQuestion = questionAdded[id].split('@#t');
    totalMarks = parseInt(selectQuestion[2]);
    questionAdded.splice(id, 1); 
    makeReadable();
}

function questionConverter(question){
    var k = '';
    question.forEach(function(item, index, array){
        k+=item;
        if (index === array.length - 1) {
            $('.preview').html(k);  
        } 
    })
}

function checkValidation(){
    var error = '';
    if($('#testStartingDate').val() == ''){
        error += 'Please fill test starting date.\n';
    }
    if($('#testName').val() == ''){
        error += 'Please fill test name.\n';
    }
    if($('#testStartingTime').val() == ''){
        error += 'Please fill test starting time.\n';
    }
    if($('#testEnd').val() == ''){
        error += 'Please fill test ending time.\n';
    }
    if(questionAdded.length == 0){
        error += 'There is no question.\n';
    }
    return error;
}



function publishTest(){
    var valid = checkValidation();
    if(valid != ''){
        alert(valid);
    } else {
        var finalQuestionListToString = questionAdded.join('@#newQuestion');
        $.ajax({
            url:'test/addTest.php',
            type:'post',
            data:{'question':finalQuestionListToString,
                'testName':$('#testName').val(),
                'testStartingDate':$('#testStartingDate').val(),
                'testStartingTime':$('#testStartingTime').val(),
                'testEnd':$('#testEnd').val(),
                'class': <?php echo $grade;?>,
                },
            onprogress:function(){
                $('body').html('<h1>Share Link Will Be Showed Here</h1>');
            },
            success:function(result){
                $('body').html('<h1>'+result+'</h1>');
            }
        });
    }
}

$(document).ready(function(){
    var option = false;
    $("select#questionType").change(function(){
        var optionSelected = $(this).children("option:selected").val();
        if (optionSelected != 'short'){
            if (optionSelected == 'blank'){
                $('#options').get(0).type = 'hidden';
                $('#loptions').text('');
                alert("don't forget to add @blank at the place where a blank");
                option = false;
            } else {
                $('#options').get(0).type = 'text';
                $('#loptions').text('Write Options here');
                option = true;
            }
        } 
        else {
            $('#options').get(0).type = 'hidden';
            $('#loptions').text('');
            option = false;
        }   
    });

    $('#testName').focusout(function(){
        var testName = $('#testName').val();
        $.ajax({
            url:'test/checkTestName.php',
            type:'post',
            data:{'testName':testName,'grade':<?php echo $grade;?>},
            success:function(result){
                if(result == 'yes'){
                    alert('Test allready exgist');
                    $('#testName').val('');
                }
            }
        })
    });

    $('#doneAddNow').click(function(){
        if($('#marks').val() == ''){
            alert('marks can\'t be empty');
        } else {
            if($('#options').val() == '' && $('#options').attr('type') != 'hidden'){
                alert('Option can\'t be empty for this kind of question');
            }
            else if($('select#questionType').children("option:selected").val() == 'inValid'){
                alert('Select a Question Type')
            }
            else {
                var question = $('#question').val();
                var questionType = $('select#questionType').children("option:selected").val();
                var marks = $('#marks').val();
                var realizer = question+'@#t'+questionType+'@#t'+marks;
                if(option){
                    realizer += '@#t'+$('#options').val();
                }
                questionAdded.push(realizer);
                $('#question').val('');
                $('#options').val('');
                makeReadable();
            }
        }
    });
});

</script>
</html>