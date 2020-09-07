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


require('../connection.php');
$class = $_GET['class'];
$tableName = "$class@student" ;

$sql = "select rollno,name,password from `$tableName`";
$stmt = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Manger</title>
    <link rel="icon" href="../assert/favico.png" type="image/png" sizes="16x16">
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
    </style>
</head>
<body>
    <input type="text" name="studentSearch" id="studentSearch" placeholder="search here"> <input type="button" id="search" value="search"><br>

    <div id="add"></div>
    <table id="searched"></table>

    <h2>Add Student here</h2>
    <label for="studentName">Student Name</label><br>
    <input type="text" name="studentName" id="studentName" placeholder="Enter Student Name"><br>
    <label for="rollno">Roll no</label><br>
    <input type="number" name="rollno" id="rollno" placeholder="Enter rollno"><br>
    <label for="password">Password -randomly genrated default</label><br>
    <input type="text" name="password" id="password" placeholder="Enter password" ><p></p>
    <button onclick="addStudnetinDb()"> Add</button>

    <p></p>

    <table id="student">
        <tr>
        <th>Roll no.</th>
        <th>Name</th>
        <th>Password</th>
        </tr>
        <?php 
            while($result = $stmt->fetch(PDO::FETCH_NUM)){
                echo "<tr>";
                echo"<td>".$result[0]."</td>";
                echo "<td>".$result[1]."</td>";
                echo "<td>".$result[2]."</td>";
                echo "</tr>";
            }
        ?>

    </table>

</body>
<script>

    function generateP() { 
        var pass = ''; 
        var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' +  'abcdefghijklmnopqrstuvwxyz0123456789'; 
        for (i = 1; i <= 8; i++) { 
            var char = Math.floor(Math.random() * str.length + 1);     
                pass += str.charAt(char) 
            } 
            return pass; 
        } 
  
    function addStudnetinDb(){
        var name = $('#studentName').val().toLocaleLowerCase();
        var rollno = $('#rollno').val();
        var password = $('#password').val();
        $.ajax({
            url:'student/addSudent.php',
            type:'post',
            data:{'name': name,'rollno':rollno,'password':password,'class':'<?php echo $tableName;?>'},
            success:function(result){
                alert(result);
                $('#studentName').val('');
                $('#rollno').val('');
                $('#password').val(generateP());
                $('#student').append("<tr><td>"+rollno+"</td><td>"+name+"</td><td>"+password+"</td></tr>");
            }
        });
    }
    
    $(document).ready(function(){

        $('#password').val(generateP());

        $('#studentSearch').keyup(function(){
            var searched = $(this).val();
            if(searched == ''){
                $('#searched').html('');
            } else {
                $.ajax({
                    url:'student/searchStudent.php',
                    type:'post',
                    data:{'searched':searched,'class':'<?php echo $tableName;?>'},
                    success:function(result){
                        if(result == ''){
                            $('#searched').html('<h1>Oops! NO result found</h1>')
                        } else {
                            $('#searched').html('<h1>result</h1><tr><th>Roll no.</th><th>Name</th><th>Password</th></tr>'+result);
                        }
                    }
                });
            }
        })
    });


</script>
</html>