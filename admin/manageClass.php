<?php 
    require('../connection.php');
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Class</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" href="../assert/favico.png" type="image/png" sizes="16x16">
    
</head>
<body>
    <input type="text" name="search" id="classSearch" placeholder="class name including section">
        <p></p>
    <button onclick="addClass()">addClass</button> 
    <div id='searchResult'></div>
    <div id="addClass"></div>
    <div id="dataOfClass"></div>
</body>
<script>
    function addClass(){
        $('#addClass').html('<input type="text" id="className" placeholder="Enter class with section like"><button onclick="addClassInDb()">Add Class</button>');
    }

    function addClassInDb(){
        var className = $('#className').val();
        if(className == ""){
            alert('Class Name Cann\'t empty');
        } else {
            $.ajax({
                url:'manageClass/addClass.php',
                type:"post",
                data:{'className':className},
                success:function(result){
                    alert(result);
                    getClass();
                }
            })
        }
    }
    function getClass(){
        $.ajax({
            url:"manageClass/getClass.php",
            success:function(result){
                if(result !=''){
                    result= "<div id='classes'>"+result+"</div>";
                } else {
                    result = "<p></p><h1>NO class added till yet</h1>";
                }
                $("#dataOfClass").html(result);
            }
        })
    }


    getClass();
    $(document).ready(function(){
        $('#classSearch').keyup(function(){
            var value = $(this).val();
            
            if(value == ""){
                $('#searchResult').html("");
            } else {
                $.ajax({
                    url:'manageClass/searchClass.php',
                    type:'post',
                    data:{'data':value},
                    success: function(result){
                        if(result== ''){
                            $('#searchResult').html("<h1>opps!No result fund</h1>")
                        } else {
                            $('#searchResult').html("Search Result<br>"+result+"<br><hr>");
                        }
                    }
                });
            }
        });
    });
</script>
</html>