<?php 
require('../connection.php');

if(isset($_GET['bypass'])){
    if ($_GET['bypass'] != 'true'){
        header('location:../index.php');
    }
}else {
    header('location:../index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="icon" href="../assert/favico.png" type="image/png" sizes="16x16">

</head>
<?php
if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD']== "POST"){
    $name = testInput($_POST['name']);
    $password = testInput($_POST['password']);
    $sql = "select userName,password,level from admin where userName=? ";
    $stmt=$conn->prepare($sql);
    $stmt->execute([$name]);
    $result = $stmt->fetch(PDO::FETCH_NUM);

    if(sizeof($result) > 0){
        $DBPassword = $result[1];
        if(password_verify($password,$DBPassword)){
            session_start();
            $_SESSION["level"] = $result[2];
            header('location:manageClass.php?bypass=$2y$10$bBtdlEP84gJdhfkkhTd1xevlRK.r9q4MgCkGl64TUgoMT02tbUvTi');
        } else {
            echo "Incorrect Password or UserName";

        }
    } else {
        echo "Incorrect Password or UserName";
    }
}
?>
<body>
    <div class="container">
        <h1>Open mind</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <input type="text" name="name" placeholder="Enter User Name"><br>
        <input type="password" name="password" placeholder="Enter Password"><br>
        <input type="submit" name="submit" value="Sign Up">
        </form>
    </div>

</body>
</html>