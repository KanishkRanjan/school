<?php 
$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';

session_start(); 
$classInSession = $_SESSION["class"];
$testIdInSession = $_SESSION["testId"];
if(isset($_SESSION["rollno"]) && isset($_SESSION["class"]) && isset($_SESSION["testId"]) && isset($_SESSION['section'])){ 
    if(time()-$_SESSION["login_time_stamp"] >10800 ){ 
        session_unset(); 
        session_destroy(); 
        header("Location:login.php?class=".$classInSession."&testId=".$testIdInSession.""); 
    } 
} else { 
    header("Location:login.php?class=".$classInSession."&testId=".$testIdInSession.""); 
} 

require('connection.php');

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test of <?php echo $_SESSION["class"]; ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/test.css">
    <link rel="icon" href="assert/favico.png" type="image/png" sizes="16x16">
    <script src="https://kit.fontawesome.com/27a4271790.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet"> 
</head>
<body>
<?php 

    $rollno = $_SESSION["rollno"];
    $class = $_SESSION["class"];
    $testId = $_SESSION["testId"];
    $section = $_SESSION['section'];
    $datetime = new DateTime();
    $currentTime = $datetime->format('H:i');
    $table ="test_".$class;
    
    $stmt = $conn->prepare("select * from `$table` where id=?");
    $stmt->execute([$testId]);
    
    $testInformation = $stmt->fetch(PDO::FETCH_NUM);
    
    $testName = $testInformation[1];
    $startingTime = $testInformation[3];
    $endingTime = $testInformation[4];
         
    function minutes($timeTaken){
      $timeTaken = explode(':', $timeTaken);
      return (int)($timeTaken[0]*60) + $timeTaken[1] ;
    }
    
    $startingTimem  = minutes($startingTime);
    $endingTimem = minutes($endingTime); 

    if(minutes($currentTime) > $startingTimem){
      $endingTimem -= minutes($currentTime)-$startingTimem;
    }    
    
    
    $timeGiven =$endingTimem - $startingTimem;

    $tableName = "result_".$class."@".$testId;
    $checkAttempt = $conn->prepare("select * from `$tableName` WHERE rollno = ?");
    $checkAttempt->execute([$rollno]);
    $checkAttempt= $checkAttempt->fetch();
?>
    <div class="loader">
        <div class="object"></div>
        <div class="loader-header">
            <h1>Hold on!</h1><br>
            <p><?php 
                  if(!$testInformation){
                     die("Test id is invalid try to open only by the shared link");
                  }  
                  if($testInformation[2] != date("Y-m-d")){
                     die("This test had ended already on ".$testInformation[2]);
                  }
                  if($timeGiven <= 0){
                    die("This test got ended at $endingTime");
                  }
                  if($checkAttempt){
                     die("You already have attempted this test. ");
                  } else {
                    $checkTable = "result_".$class."@".$testId;
                    $stopHim = $conn->prepare("INSERT INTO `$checkTable` (`id`, `rollno`, `answer`, `section`) VALUES (NULL, :rollno, '', :section);");  
                    $stopHim->execute(['rollno' => $rollno,'section' => $section]);
                  }
                ?>
            <h2>Test data is downloading...</h2>
            </p>
        </div>
    </div>
    <div class="testInformation">
        <div class="element">
          <h1>Test Name :- <?php echo $testName;?></h1>
        </div>
        <div class="element">
          <p>Test Start from <?php echo $startingTime;?></p>
        </div>
        <div class="element">
         <p>Test end at <?php echo $endingTime;?> </p> 
        </div>
        <div class="element">
          <p>Conducted on <?php echo $testInformation[1];?></p>
        </div>
        <div class="element">
          <p>Class <?php echo $class;?></p>
        </div>
        <div class="element">
          <button onclick="removeMe()">Okay</button>
        </div>
      </div>
    <div class="nav">
      <div class="wrapper">
      <div class="bars">
        <span class="line top"></span>
        <span class="line middle"></span>
        <span class="line bottom"></span>
      </div>
    </div>
      <div class="time">
      <p>
      <span id="hour"></span>: <span id="minute"></span> :
      <span id="second"></span>
    </p>
  </div>
</div>
<div class="bigBox">
      <div class="menu">
        <div class="title">
          <h1><?php echo $testName;?></h1>
        </div>
        <div class="menu-title" style="margin: 25px 0"> 
          <h1>Choice Question</h1>
        </div>
        <div class="choseQuestion">
        </div>

          <input
            type="button"
            value="Sumbit Test"
            class ="secondSubmit"
          />

      </div>
      <div class="displayQuestion">
        <div class="box"></div>
        <div class="control">
          <button onclick="previous()" class="previous"> <i class="fas fa-arrow-left"></i>previous</button>
          <button onclick="clearRespond()" class="clearRespond">clear respond</button>
          <button onclick="next()" class="next">next <i class="fas fa-arrow-right"></i></button>
        </div>
      </div>
</div>

</body>

<script>
  var numberOfQuestion =0 ;
  var timeHours,timeMinutes,second = 0;

    $.ajax({
      url:'test/testPaper.php',
      type:"post",
      data:{'class':'<?php echo $class;?>','section':'<?php echo $section ?>','testId':'<?php echo $testId ?>'},
      success:function(result){
          $('.box').html(result);
          numberOfQuestion += parseInt($('input[name=totalQuestion]').val())-1;
          let onQuestion = 0;
          while(numberOfQuestion > onQuestion){
            onQuestion++;
            $('.choseQuestion').append('<div class="number" onclick=move('+onQuestion+')>'+onQuestion+'</div>');
          }
          $('#1').attr('class', 'questionBoxActive');
          timeHours = ~~(parseInt('<?php echo $timeGiven;?>')/60);
          timeMinutes = (parseInt('<?php echo $timeGiven;?>') - (timeHours*60) );
        },
    });

    setInterval(function(){
      second--;
      if(second <= 0){
        if(timeMinutes !== 0){
          timeMinutes--;
          second= 60;
        } else{
          if(timeHours !== 0){
            timeHours--;
            timeMinutes = 60;
          } else {
            alert("Test got end");
            $('#test').submit();
          }
        }
      } 
      
      $("#second").text(("0"+second).slice(-2));
      $("#minute").text(("0"+timeMinutes).slice(-2));
      $("#hour").text(("0"+timeHours).slice(-2));
    }, 1000);

    $(".bars").click( function() {
      menuSwitch()
    });

    function removeMe(){
      $('.testInformation').css("display","none")
    }
    function menuSwitch(){
      $(".bars").toggleClass("close");
      if($(".bars").hasClass("close")){
        $(".menu").css('left','0px');
      } else {
        $(".menu").css('left','-100%');
      }
    }
    $(".secondSubmit").click(function(){
      let currentQuestion = parseInt($(".questionBoxActive").attr('id'));
      $(".alerter").css('display',"flex");
      $("#"+currentQuestion).attr("class","questionBox");
      $(".control").css("display","none");

      if(screen.width < 650){
        menuSwitch();
      }
    })

    function move(id){
      let currentQuestion = parseInt($(".questionBoxActive").attr('id'))
      $("#"+currentQuestion).attr("class","questionBox");
      $("#"+id).attr("class","questionBoxActive");
      $(".alerter").css("display","none");
      $(".control").css("display","flex");
      if(screen.width < 650){
        menuSwitch();
      }
    }

    function cancel(){
      $("#"+numberOfQuestion).attr("class","questionBoxActive");
      $(".alerter").css("display","none");
      $(".control").css("display","flex");
      move(numberOfQuestion)
    }

    $(document).ready(function(){
      $(".previous").prop("disabled",true).css("background-color","#616161");
      $(".loader").fadeOut("slow");
      $(".control").css('display','flex');
    });

    function previous(){
      let currentQuestion = parseInt($(".questionBoxActive").attr('id'));
      let toSwitch = parseInt(currentQuestion-1);
      if(currentQuestion >= 1){
        $(".next").prop("disabled",false).css("background-color","#333333");
        $("#"+toSwitch).attr("class","questionBoxActive");
        $("#"+currentQuestion).attr("class","questionBox");
        if(toSwitch <= 1 ){
          $(".previous").prop("disabled",true).css("background-color","#616161");
        } 
      } 
    }

    function next(){
      let currentQuestion = parseInt($(".questionBoxActive").attr('id'));
      if(currentQuestion <= numberOfQuestion){
        let toSwitch = parseInt(currentQuestion)+1;
        $(".previous").prop("disabled",false).css("background-color","#333333");
        $("#"+toSwitch).attr("class","questionBoxActive");
        $("#"+currentQuestion).attr("class","questionBox");
        if(toSwitch> numberOfQuestion){
          $("#"+currentQuestion).attr("class","questionBox");
          $(".alerter").css("display","flex");
          $(".control").css("display","none");
        }
      }
    }

    function clearRespond(){
      let currentQuestion = parseInt($(".questionBoxActive").attr('id'));
      const numberOfOption = parseInt($(".questionBoxActive input[name=optionOn_"+currentQuestion+"]").val());
      let optionOn = 0; 
      let d = $("#questionNumber"+currentQuestion+"_"+optionOn).attr('type');
      if(d == "radio"){
        while(optionOn < numberOfOption){
           $("#questionNumber"+currentQuestion+"_"+optionOn).prop("checked", false);
          optionOn++;
        } 
      }
      else if(d == "checkbox"){
        while(optionOn < numberOfOption){
          $("#questionNumber"+currentQuestion+"_"+optionOn).prop("checked", false);
          optionOn++;
        } 
      }
      else if(d == "text"){
        while(optionOn < numberOfOption){
          $("#questionNumber"+currentQuestion+"_"+optionOn).val("");
          optionOn++;
        } 
      }
      else {
        $("#questionNumber"+currentQuestion+"_"+optionOn).val("");
      }
    }
</script>
</html>