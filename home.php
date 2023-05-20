<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_name"]) && !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Página de inicio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4 mb-4">Bienvenido, <?php echo $user_name; ?></h2>
        <p>Esta es la página de inicio.</p>
        <a class="btn btn-primary" href="logout.php">Cerrar sesión</a>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>