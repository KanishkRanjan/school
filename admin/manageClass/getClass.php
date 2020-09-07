<?php 
require('../../connection.php');
$result = $conn->query('show tables');
        $classess = array();
        while($row = $result->fetch(PDO::FETCH_NUM)){
            $name = explode('_',$row[0])[0];
            if($name == 'class'){
                $class = explode('@',$row[0])[0];
                if(!in_array($class,$classess)){
                    array_push($classess,$class);
                    echo '<a href="main.php?class='.$class.'&bypass=$2y$10$bBtdlEP84gJdhfkkhTd1xevlRK.r9q4MgCkGl64TUgoMT02tbUvTi">'.$class.'</a><br>';
                } 

            }
        }
?>