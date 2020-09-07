<?php 
if(isset($_POST['data'])){
    require('../../connection.php');
    $data = $_POST['data'];
    $tableName = "class_$data@student";
    $stmt = $conn->query('show tables');
    while($result = $stmt->fetch(PDO::FETCH_NUM)){
        if($result[0] == $tableName){
            echo '<a href="main.php?class='.$data.'&bypass=$2y$10$bBtdlEP84gJdhfkkhTd1xevlRK.r9q4MgCkGl64TUgoMT02tbUvTi">class_'.$data.'</a><br>';
            
        }
    }
}else {
    header('../../index.php');
}

?>