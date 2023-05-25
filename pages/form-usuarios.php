<?php
session_start();
require("../src/dao/UserDao.php");
//Verifica la existencia de Session
if (!isset($_SESSION["user_name"]) && !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
} else {
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];
}
$modalError = "";
$modalWarning = "";
if (!isset($_GET["act"])) {
    error_log("param GET act undedined");
    header("Location: error.php");
    exit();
}
$id = 0;
if (($_POST["id"]!="0") && isset($_POST["nombre"]) && isset($_POST["pwd"]) && isset($_POST["publico"])) {
    
    try{
        $usuario = getUserById($_POST["id"]);
        $usuarioUpdate = $usuario;
        if($usuario->getFullName()!=$_POST["nombre"]){
            $usuarioUpdate->setFullName($_POST["nombre"]);
        }
        if($_POST["pwd"]!=""){
            if(!existUserByIdAndPassword($_GET["id"],$_POST["pwd"])){
                $usuarioUpdate->setPwd($_POST["pwd"]);
            }
        }
        if($usuario->getStatus()!=$_POST["publico"]){
            $usuarioUpdate->setStatus($_POST["publico"]);
        }
        if(updateUser($usuarioUpdate)>0){
            header("Location: mantenedor-usuarios.php");
            exit();
        }else{
            $modalError = '<div class="alert alert-danger" role="alert"><strong>Error : </strong> No se ha podido guardar el usuario.</div>';
        }
    }catch(UserException $ue){
        $modalWarning = '<div class="alert alert-warning" role="alert"><strong>Warning : </strong> '.$eu->getMessage().' </div>';
    }catch(Exception $e){
        error_log($e->getMessage());
        header("Location: error.php");
        exit();

    }
}
if (isset($_POST["user"]) && isset($_POST["nombre"]) && isset($_POST["pwd"]) && isset($_POST["publico"])) {
    if (existUserByName(trim($_POST["user"]))) {
        $modalWarning = '<div class="alert alert-warning" role="alert"><strong>Warning : </strong> El usuario ' . $_POST["user"] . ' ya existe en el sistema.</div>';
    } else {

        $userNew = new User();
        $userNew->setName(trim($_POST["user"]));
        $userNew->setFullName($_POST["nombre"]);
        $userNew->setPwd($_POST["pwd"]);
        $userNew->setStatus($_POST["publico"]);
        if (saveUser($userNew)) {
            header("Location: mantenedor-usuarios.php");
            exit();
        } else {
            $modalError = '<div class="alert alert-danger" role="alert"><strong>Error : </strong> No se ha podido guardar el usuario.</div>';
        }
    }
}

switch ($_GET["act"]) {
    case "new":

        break;
    case "edit":
        if (!isset($_GET["id"])) {
            error_log("param GET id undedined");
            header("Location: error.php");
            exit();
        }
        $id =
            $usuario = getUserById($_GET["id"]);
        break;
    case "del";
        if (!isset($_GET["id"])) {
            error_log("param GET id undedined");
            header("Location: error.php");
            exit();
        }
        deleteUserById($_GET["id"]);
        header("Location: mantenedor-usuarios.php");
        exit();
    default:
        error_log("The param act is not valid " . $action);
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
    <title>Formulario Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            padding: 40px;
        }

        .menu-link {
            color: white;
        }

        .menu-link-dark {
            color: black;
        }

        .navbar-nav {
            margin-left: auto;
        }
    </style>
</head>

<body class="p-3 m-0 border-0 bd-example">
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark " data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PRO301</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active menu-link" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="mantenedor-areas.php">Área</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="mantenedor-usuarios.php">Usuarios</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle menu-link btn btn-primary" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $user_name ?>
                        </a>
                        <ul class="dropdown-menu ">
                            <li><a class="btn btn-danger dropdown-item menu-link-dark btn btn-danger" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </li>


                </ul>
            </div>
        </div>
    </nav>
    <section>
        <div class="container text-center">
            <div class="row p-3"></div>
            <div class="row p-3">
                <div class="col-12">
                    <h1><?php echo ($_GET['act'] == "new") ? "Formulario Nuevo Usuario" : "Formulario Editar Usuario"; ?></h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php echo $modalError; ?><br>
                    <?php echo $modalWarning; ?><br>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="" method="post">
                        <div class="container">
                        <div class="row">
                        <input class="form-control" type="number" name="id" id="id" hidden <?php echo ($_GET["act"] == "edit") ? " value=" . $usuario->getId() . "" : " value=0"; ?>>
                        </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="user">Usuario</label>
                                </div>
                                <div class="col-8">
                                    <input class="form-control" required type="text" name="user" id="user" <?php echo ($_GET["act"] == "edit") ? " value='" . $usuario->getName() . "' disabled" : ""; ?>>
                                </div>
                            </div>
                            <div class="row p-3"></div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="nombre">Nombre</label>
                                </div>
                                <div class="col-8">
                                    <input class="form-control" type="text" required name="nombre" id="nombre" <?php echo ($_GET["act"] == "edit") ? " value='" . $usuario->getFullName() . "' disabled" : ""; ?>>
                                </div>
                            </div>
                            <div class="row p-3"></div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="pwd">Contraseña</label>
                                </div>
                                <div class="col-8">
                                    <input class="form-control" type="password"  name="pwd" id="pwd" <?php echo ($_GET["act"] == "edit") ? "' disabled" : " required"; ?>>
                                </div>
                            </div>
                            <!--<div class="row p-3"></div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="re-pwd">Repetir Contraseña</label>
                                </div>
                                <div class="col-8">
                                    <input class="form-control" type="password" name="re-pwd" id="re-pwd" <?php echo ($_GET["act"] == "edit") ? "' disabled" : ""; ?>>
                                </div>
                            </div>
    -->
                            <div class="row p-3"></div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="publico">Publico</label>
                                </div>
                                <div class="col-8">
                                    <input type="radio" name="publico" required id="inputRadioPublicoSi" value="0" <?php echo ($_GET["act"] == "edit") ? (($usuario->getStatus()==0)?" checked ":"")."' disabled" : ""; ?>>No</input>
                                    <br>
                                    <input type="radio" name="publico" id="inputRadioPublicoNo" value="1" <?php echo ($_GET["act"] == "edit") ? (($usuario->getStatus()==1)?" checked ":"")."' disabled" : ""; ?>>Si</input>
                                </div>
                            </div>
                            <div class="row p-3"></div>
                            <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <?php echo ($_GET["act"] == "edit") ? '<input class="btn btn-primary"type="button" id="btn-edit" value="Editar" >' : ""; ?>
                                    <input class="btn btn-primary" type="submit" id="btn-save" value="Guardar" <?php echo ($_GET["act"] == "edit") ? "' disabled" : ""; ?>>
                                </div>
                                <div class="col-4"></div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/fun-usuarios.js"></script>
</body>

</html>