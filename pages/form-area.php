<?php
session_start();
require("../src/dao/AreaDao.php");

//Verifica la existencia de Session
if (!isset($_SESSION["user_name"]) && !isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
} else {
    $user_id = $_SESSION["user_id"];
    $user_name = $_SESSION["user_name"];
}
if (!isset($_GET["act"])) {
    error_log("param GET act undedined");
    header("Location: error.php");
    exit();
}
$modalError = "";
if (isset($_POST["nombre_area"]) && isset($_POST["descripcion"]) && isset($_POST["estado"]) && isset($_FILES["fileImg"])) {
    $nombre_archivo = $_FILES["fileImg"]["name"];
    $area = new Area();
    $area->setNameArea($_POST["nombre_area"]);
    $area->setDescription($_POST["descripcion"]);
    $area->setImg($nombre_archivo);
    $area->setStatus($_POST["estado"]);
    $rutaTemporal = $_FILES['fileImg']['tmp_name'];
    $rowAfect = saveArea($area);
    if ($rowAfect >= 1) {
        $area = getByNombreAreaAndDescripcionAndImagenAndEstado($area);
        $isSaveFile = !move_uploaded_file($rutaTemporal, "/var/www/html/prueba3/img/" . $nombre_archivo);
        if ($isSaveFile) {
            deleteById($area->getCode());
            $modalError = '<div class="alert alert-danger" role="alert"><strong>Error</strong> al guardar</div>';
        } else {
            #header("Location: mantenedor-areas.php");
            header("Location: form-area.php?act=edit&cod=" . strval($area->getCode()));
            exit();
        }
    }
}

$tagInputNombreAreaIni = '<input class="form-control" type="text" name="nombre_area"';
$tagInputNombreAreaFin = '>';
$tagInputNombreArea = '';

$tagTextAreaDescriptionIni = '<textarea class="form-control" id="myTextarea" name="descripcion"';
$tagTextAreaDescriptionFin = '</textarea>';
$tagTextAreaDescription = '';

$tagImgIni = '<div id="imagenDiv">';
$tagImgFin = "</div>";
$tagImg = "";


$tagInputFileIni = '<input type="file" class="custom-file-input" id="customFile" name="fileImg"';
$tagInputFileFin = '>';
$tagInputFile = '';

$tagInputRadioButtomSiIni = '<input name="estado" type="radio" value="1"';
$tagInputRadioButtomSiFin = '>Si</input>';
$tagInputRadioButtomSi = '';


$tagInputRadioButtomNoIni = '<input name="estado" type="radio" value="0"';
$tagInputRadioButtomNoFin = '>No</input>';
$tagInputRadioButtomNo = '';

$tagButtomGuardarIni = '<button class="btn btn-primary"';
$tagButtomGuardarFin = '>Guardar</button>';
$tagButtomGuardar = '';

$tagButtomEditar = '';


$area = null;
$textAreaEdit = '';

switch ($_GET["act"]) {
    case "del":
        if (!isset($_GET["cod"])) {
            error_log("param GET cod undedined");
            header("Location: error.php");
            exit();
        }
        $cod = intval($_GET["cod"]);
        deleteById($cod);
        header("Location: mantenedor-areas.php");
        exit();
    case "new":
        $tagInputNombreArea = $tagInputNombreAreaIni . $tagInputNombreAreaFin;
        $tagTextAreaDescription = $tagTextAreaDescriptionIni . '>' . $tagTextAreaDescriptionFin;
        $tagInputFile = $tagInputFileIni . $tagInputFileFin;
        $tagInputRadioButtomSi = $tagInputRadioButtomSiIni . $tagInputRadioButtomSiFin;
        $tagInputRadioButtomNo = $tagInputRadioButtomNoIni . $tagInputRadioButtomNoFin;
        $tagButtomGuardar = $tagButtomGuardarIni . $tagButtomGuardarFin;
        $tagImg = $tagImgIni . $tagImgFin;
        break;
    case "edit":
        if (!isset($_GET["cod"])) {
            error_log("param GET cod undedined");
            header("Location: error.php");
            exit();
        }
        $cod = intval($_GET["cod"]);
        try {
            $area = getAreaById($cod);
        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: error.php");
            exit();
        }
        $tagInputNombreArea = $tagInputNombreAreaIni . 'value="' . ($area->getNameArea()) . '" disabled' . $tagInputNombreAreaFin;
        $tagTextAreaDescription = $tagTextAreaDescriptionIni . ' disabled >' . ($area->getDescription()) . $tagTextAreaDescriptionFin;
        $tagInputFile = $tagInputFileIni . " disabled " . $tagInputFileFin;


        if (intval($area->getStatus()) == 0) {
            $tagInputRadioButtomNo = $tagInputRadioButtomNoIni . " checked disabled " . $tagInputRadioButtomNoFin;
            $tagInputRadioButtomSi = $tagInputRadioButtomSiIni . " disabled " . $tagInputRadioButtomSiFin;
        }
        if (intval($area->getStatus()) == 1) {
            $tagInputRadioButtomSi = $tagInputRadioButtomSiIni . " checked disabled " . $tagInputRadioButtomSiFin;
            $tagInputRadioButtomNo = $tagInputRadioButtomNoIni . " disabled " . $tagInputRadioButtomNoFin;
        }
        $contenido_imagen = file_get_contents("../img/" . $area->getImg());
        $base64_imagen = base64_encode($contenido_imagen);
        $tagImg = $tagImgIni . '<img style="width:150px; heigth:85px" src="data:image/jpeg;base64,' . $base64_imagen . '">' . $tagImgFin;

        $tagButtomGuardar = $tagButtomGuardarIni . " disabled " . $tagButtomGuardarFin;
        $tagButtomEditar = '<input type="button" class="btn btn-primary" id=btnEditar value="Editar">';
        $textAreaEdit = 'readonly : 1';
        break;

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
    <title>Mantenedor de &Acute;reas</title>
    <script type="text/javascript" src='../js/tinymce/tinymce.min.js'></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: '#myTextarea',
            width: 800,
            height: 400,
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
                'media', 'table', 'emoticons', 'template', 'help'
            ],
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons | help',
            menu: {
                favs: {
                    title: 'My Favorites',
                    items: 'code visualaid | searchreplace | emoticons'
                }
            },
            menubar: 'favs file edit view insert format tools table help'
            <?php echo $textAreaEdit; ?>
        });
    </script>


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
                    <h1><?php echo ($_GET['act'] == "new") ? "Nueva Area" : "Editar Area"; ?></h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php echo $modalError; ?><br>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-3"></div>
                            <div class="col-9">
                                <input type="text" name="id" hidden>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3"><label>Nombre Área</label></div>
                            <div class="col-9">
                                <?php echo $tagInputNombreArea; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-3"><label>Descripción</label></div>
                            <div class="col-9">
                                <?php echo $tagTextAreaDescription; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3"><label>Imagen</label></div>
                            <div class="col-3">
                                <div class="custom-file">
                                    <?php echo $tagInputFile; ?>
                                    <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                                </div>
                            </div>
                            <div class="col-6" style="height:150px">
                                <?php echo $tagImg; ?>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-4"><label>Publicado</label></div>
                            <div class="col-8">
                                <?php
                                echo $tagInputRadioButtomSi;
                                echo "<br>";
                                echo $tagInputRadioButtomNo;
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4"></div>
                            <div class="col-4">
                                <?php echo $tagButtomEditar; ?>
                            </div>
                            <div class="col-4">
                                <?php echo $tagButtomGuardar; ?>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </form>
 
                </div>
            </div>
        </div>
    </section>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/main.js"></script>
</body>

</html>