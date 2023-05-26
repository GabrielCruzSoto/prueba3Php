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
function generateNameFile(string $name)
{
    $extensionFile = pathinfo($name, PATHINFO_EXTENSION);

    $serialTimestap = strval((new DateTime())->getTimestamp());
    return $serialTimestap . "." . $extensionFile;
}
if (count($_POST) != 0) {
    if ($_POST["cod"] == "0" && isset($_POST["nombre_area"]) && isset($_POST["descripcion"]) && isset($_POST["estado"]) && isset($_FILES["fileImg"])) {
        try {
            $nombre_archivo = $_FILES["fileImg"]["name"];
            $area = new Area();
            $area->setNameArea($_POST["nombre_area"]);
            $area->setDescription($_POST["descripcion"]);
            $nombreArchivoFinal = generateNameFile($nombre_archivo);
            $area->setImg($nombreArchivoFinal);
            $area->setStatus($_POST["estado"]);
            $rutaTemporal = $_FILES['fileImg']['tmp_name'];
            $rowAfect = saveArea($area);
            if ($rowAfect >= 1) {
                $area = getByNombreAreaAndDescripcionAndImagenAndEstado($area);
                $nombreArchivoFinal = generateNameFile($nombre_archivo);
                $isSaveFile = !move_uploaded_file($rutaTemporal, "/var/www/html/prueba3/img/" . $nombreArchivoFinal);
                if ($isSaveFile) {
                    deleteById($area->getCode());
                    $modalError = '<div class="alert alert-danger" role="alert"><strong>Error</strong> al guardar</div>';
                } else {
                    #header("Location: mantenedor-areas.php");
                    header("Location: mantenedor-areas.php");
                    exit();
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: error.php");
            exit();
        }
    }

    if ($_POST["cod"] != "0" && isset($_POST["nombre_area"]) && isset($_POST["descripcion"]) && isset($_POST["estado"]) && isset($_FILES["fileImg"])) {
        try {
            $areaOld = getAreaById($_POST["cod"]);
            $areaUpdate = $areaOld;

            if ($areaOld->getNameArea() != $_POST["nombre_area"]) {
                $areaUpdate->setNameArea($_POST["nombre_area"]);
            }
            if ($_FILES["fileImg"]["name"] != "") {

                $nombreArchivoFinal = generateNameFile($_FILES["fileImg"]["name"]);
                if (move_uploaded_file($_FILES['fileImg']['tmp_name'], "/var/www/html/prueba3/img/" . $nombreArchivoFinal)) {
                    $areaUpdate->setImg($nombreArchivoFinal);
                } else {
                    header("Location: error.php");
                    exit();
                }
            }
            if ($areaOld->getDescription() != $_POST["descripcion"]) {
                $areaUpdate->setDescription($_POST["descripcion"]);
            }
            if ($areaOld->getStatus() != $_POST["estado"]) {
                $areaUpdate->setStatus(intval($_POST["estado"]));
            }
            if (updateArea($areaUpdate) >= 1) {
                header("Location: mantenedor-areas.php");
                exit();
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: error.php");
            exit();
        }
    }
}






$tagInputNombreAreaIni = '<input class="form-control" type="text" name="nombre_area" id="nombre_area"';
$tagInputNombreAreaFin = '>';
$tagInputNombreArea = '';

$tagTextAreaDescriptionIni = '<textarea class="form-control" id="myTextarea" name="descripcion"';
$tagTextAreaDescriptionFin = '</textarea>';
$tagTextAreaDescription = '';

$tagImgIni = '<div id="imagenDiv"><img id="img-preview" ';
$tagImgFin = "></div>";
$tagImg = "";


$tagInputFileIni = '<input type="file" class="custom-file-input" id="customFile" name="fileImg"';
$tagInputFileFin = '>';
$tagInputFile = '';

$tagInputRadioButtomSiIni = '<input name="estado" id="inputRadioPublicoSi" type="radio" value="1"';
$tagInputRadioButtomSiFin = '>Si</input>';
$tagInputRadioButtomSi = '';


$tagInputRadioButtomNoIni = '<input name="estado" id="inputRadioPublicoNo" type="radio" value="0"';
$tagInputRadioButtomNoFin = '>No</input>';
$tagInputRadioButtomNo = '';

$tagButtomGuardarIni = '<button class="btn btn-primary" id="btn-save" ';
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
        $tagInputNombreArea = $tagInputNombreAreaIni . " required " . $tagInputNombreAreaFin;
        $tagTextAreaDescription = $tagTextAreaDescriptionIni . ' >' . $tagTextAreaDescriptionFin;
        $tagInputFile = $tagInputFileIni . " required " . $tagInputFileFin;
        $tagInputRadioButtomSi = $tagInputRadioButtomSiIni . " required " . $tagInputRadioButtomSiFin;
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
            if ($area == null) {
                throw new Exception("getAreaById return area null");
            };
        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: error.php");
            exit();
        }

        $tagInputNombreArea = $tagInputNombreAreaIni . 'value="' . ($area->getNameArea()) . '" disabled' . $tagInputNombreAreaFin;
        $tagTextAreaDescription = $tagTextAreaDescriptionIni . '>' . ($area->getDescription()) . $tagTextAreaDescriptionFin;
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
        $tagImg = $tagImgIni . ' style="width:150px; heigth:85px" src="data:image/jpeg;base64,' . $base64_imagen . '"' . $tagImgFin;

        $tagButtomGuardar = $tagButtomGuardarIni . " disabled " . $tagButtomGuardarFin;
        $tagButtomEditar = '<input type="button" class="btn btn-primary" id="btn-edit" value="Editar">';

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <script type="text/javascript">
        tinymce.init({
            selector: '#myTextarea',
            width: 800,
            height: 400,
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen', 'insertdatetime',
                'media', 'table', 'emoticons', 'help'
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
            <?php echo $_GET["act"] == "edit" ? ",readonly: true" : ""; ?>
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
                                <?php echo ($_GET["act"] == "edit") ? '<input type="text" name="cod" hidden value=' . $_GET["cod"] . '>' : '<input type="text" name="cod" hidden value=0>'; ?>
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
    <script type="text/javascript" src="../js/fun-areas.js"></script>
</body>

</html>