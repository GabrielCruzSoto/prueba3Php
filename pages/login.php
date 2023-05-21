<?php
session_start();
require('../src/dao/UserDao.php');

if (isset($_SESSION["user_name"]) && isset($_SESSION["user_id"])) {
    header("Location: home.php");
    exit();
}

if (isset($_POST["user"]) && isset($_POST["pwd"])) {
    $userName = $_POST["user"];
    $pwd = $_POST["pwd"];
    try {
        $user = getUserByName($userName);

        getUserByIdAndPassword($user->getId(), $pwd);
        $_SESSION["user_name"] = $user->getName();
        $_SESSION["user_id"] = $user->getId();
        header("Location: home.php");
        exit();
    } catch (UserException $ue) {
        $error = $ue->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 40px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="mt-4 mb-4">Iniciar sesión</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de usuario:</label>
                <input type="text" class="form-control" name="user" required>
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" class="form-control" name="pwd" required>
            </div>

            <button type="submit" class="btn btn-primary">Iniciar sesión</button>

            <?php if (isset($error)) {
                echo "<p class='mt-3' style='color: red;'>$error</p>";
            } ?>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>