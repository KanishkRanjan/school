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
$class = testInput($_GET['class']);
$grade = $class[-2];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result <?php echo $grade;?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" href="../assert/favico.png" type="image/png" sizes="16x16">

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
    
    <h1>View Result of <?php echo $class;?> </h1>
    <div class="function">
        <div class="search">
            <input type="text" name="search" id="search" placeholder="Search Test"><button onclick="searched()">Search</button><p></p>
        </div>
        
        <div class="searchedResult">
            
        </div>
        <p></p>
    </div>
   
    <?php 
        $stmt  = $conn->query("SELECT `id`,`testName`,`testStartingDate`,`testStartingTime`,`testEnd` FROM `test_$grade` ORDER BY `id` DESC");
        $tests = $stmt->fetchAll(PDO::FETCH_NUM);
        if(sizeof($tests) == 0){
            echo "<h1>No Test Added</h1>";
        } else {
    ?>
            <table>
            <tr>
                <th>Test Id</th>
                <th>Test Name</th>
                <th>Test Date</th>
                <th>Starting Time</th>
                <th>Test Ends At</th>
                <th>View Result</th>
            </tr>
    <?php
            foreach($tests as $test){
    ?>
                <tr>
                    <td><?php echo $test[0];?></td>
                    <td><?php echo $test[1];?></td>
                    <td><?php echo $test[2];?></td>
                    <td><?php echo $test[3];?></td>
                    <td><?php echo $test[4];?></td>
                    <td><a href="result/detail.php?class=<?php echo $class;?>&testId=<?php echo $test[0];?>&bypass=$2y$10$bBtdlEP84gJdhfkkhTd1xevlRK.r9q4MgCkGl64TUgoMT02tbUvTi">View Detail</a></td>
                </tr>    
    <?php
            }
        }
    ?>
    </table>
</body>
<script>
    function searched(){
        var search = $('#search').val();
        if(search == ''){
            alert('Search Can\'t be empty');
        }else {
            $.ajax({
                url:'result/searchTest.php',
                type:'post',
                data:{'grade':<?php echo $grade;?>,'search':search,'class':'<?php echo $class;?>'},
                success:function(result){
                    if(result != ''){
                        $('.searchedResult').html('<h2>Result :-</h2><table><tr><th>Test Id</th><th>Test Name</th><th>Test Date</th><th>View Result</th></tr>'+result+'</table>');
                    }else {
                        $('.searchedResult').html('<h2>Result Not Found for'+search+'</h2>')
                    }
                }
            })
        }
    }

</script>
</html>