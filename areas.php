<?php
session_start();
require("src/dao/AreaDao.php");
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION["user_name"]) && !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];
$listAreas=array();
try {
    $listAreas = getAllAreas();
} catch (Exception $e) {
    header("Location: error.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenedor de &Acute;reas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding: 40px;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <h2 class="mt-4 mb-4">Mantenedor Áreas</h2>
    </div>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-primary" href="home.php">Home</a>
            <a class="btn btn-primary" href="areas.php">&Aacutereas</a>
            <a class="btn btn-primary" href="usuarios.php">Usuarios</a>
        </div>
        <div class="col-3"></div>
        <div class="col-3">
            <a class="btn btn-ligth" href="">
                <?php echo $user_name; ?>
            </a>
            <a class="btn btn-primary" href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-12"><br></div>
    </div>
    <div class="row">
        <div class="col-12"><br></div>
    </div>
    <div class="row">
        <div class="col-12"><br></div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-4">
            <a class="btn btn-secondary" href="form-new-area.php">Nuevo Registro</a>
        </div>
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>
    <div class="row">
        <div class="col-12"><br></div>
    </div>
    <div class="row">
        <div class="col-12"><br></div>
    </div>
    <div class="row">
        <div class="col-4">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre Area</th>
                    <th scope="col">Publicado</th>
                    <th scope="col">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($listAreas as $area) {
                    echo "<tr>" .
                        "<th scope='row'>".strval($area->getCode())."</th>" .
                        ("<td>".$area->getNameArea()."</td>" . ("<td>" . ($area->getStatus() == 0 ? "N" : "S") . "</td>")) .
                        "<td><a class='btn btn-success' href='form-area.php?code=".$area->getCode()."'>editar</td>" .
                        "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>