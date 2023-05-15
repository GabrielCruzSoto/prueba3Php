<?php
    if(isset($_POST["user"]) && isset($_POST["pwd"])){
        echo "<script>alert('user not defined')</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="" method="post">
        <label for="user">User</label>
        <input type="text" name="user" >
        <label for="pwd">Password</label>
        <input type="password" name="pwd">
        <button>Login</button>
    </form>
</body>
</html>