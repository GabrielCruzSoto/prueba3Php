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

$tagInputNombreAreaIni = '<input class="form-control" type="text" name="nombre_area"';
$tagInputNombreAreaFin = '>';
$tagInputNombreArea = '';

$tagTextAreaDescriptionIni = '<textarea class="form-control" id="myTextarea" name="descripcion"';
$tagTextAreaDescriptionFin = '</textarea>';
$tagTextAreaDescription = '';

$tagImgIni = '<div id="imagenDiv">';
$tagImgFin = "</div>";
$tagImg = "";


$tagInputFileIni = '<input type="file" class="custom-file-input" id="customFile"';
$tagInputFileFin = '>';
$tagInputFile = '';

$tagInputRadioButtomSiIni='<input name="estado" type="radio" value="1"';
$tagInputRadioButtomSiFin='>Si</input>';
$tagInputRadioButtomSi='';


$tagInputRadioButtomNoIni='<input name="estado" type="radio" value="0"';
$tagInputRadioButtomNoFin='>No</input>';
$tagInputRadioButtomNo='';

$tagButtomGuardarIni='<button class="btn btn-primary"';
$tagButtomGuardarFin='>Guardar</button>';
$tagButtomGuardar='';

$tagButtomEditar ='';


$area = null;
$textAreaEdit='';

switch ($_GET["act"]) {
    case "new":
        $tagInputNombreArea = $tagInputNombreAreaIni . $tagInputNombreAreaFin;
        $tagTextAreaDescription = $tagTextAreaDescriptionIni .'>'. $tagTextAreaDescriptionFin;
        $tagInputFile = $tagInputFileIni.$tagInputFileFin;
        $tagInputRadioButtomSi= $tagInputRadioButtomSiIni.$tagInputRadioButtomSiFin;
        $tagInputRadioButtomNo= $tagInputRadioButtomNoIni.$tagInputRadioButtomNoFin;
        $tagButtomGuardar= $tagButtomGuardarIni.$tagButtomGuardarFin;
        $tagImg=$tagImgIni.$tagImgFin;
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
        $tagInputFile= $tagInputFileIni." disabled ".$tagInputFileFin;
        
        
        if(intval($area->getStatus())==0){
            $tagInputRadioButtomNo= $tagInputRadioButtomNoIni." checked disabled ".$tagInputRadioButtomNoFin;
            $tagInputRadioButtomSi= $tagInputRadioButtomSiIni." disabled ".$tagInputRadioButtomSiFin;
        }
        if(intval($area->getStatus())==1){
            $tagInputRadioButtomSi= $tagInputRadioButtomSiIni." checked disabled ".$tagInputRadioButtomSiFin;
            $tagInputRadioButtomNo= $tagInputRadioButtomNoIni." disabled ".$tagInputRadioButtomNoFin;
            
        }
        $contenido_imagen = file_get_contents("../img/".$area->getImg());
        $base64_imagen = base64_encode($contenido_imagen);
        $tagImg=$tagImgIni.'<img style="width:150px; heigth:85px" src="data:image/jpeg;base64,'.$base64_imagen.'">'.$tagImgFin;
 
        $tagButtomGuardar= $tagButtomGuardarIni." disabled ".$tagButtomGuardarFin;
        $tagButtomEditar ='<input type="button" class="btn btn-primary" id=btnEditar value="Editar">';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            padding: 40px;
        }
    </style>
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
</head>

<body>
    <div class="container">

        <div class="row">
            <div class="col-6">
                <a class="btn btn-primary" href="home.php">Home</a>
                <a class="btn btn-primary" href="mantenedor-areas.php">&Aacutereas</a>
                <a class="btn btn-primary" href="mantenedor-usuarios.php">Usuarios</a>
            </div>
            <div class="col-3"></div>
            <div class="col-3">
                <a class="btn btn-ligth" href="">
                    <?php echo $user_name; ?>
                </a>
                <a class="btn btn-primary" href="logout.php">Cerrar sesión</a>
            </div>
        </div>
        <div class="row">
            <h2 class="mt-4 mb-4">
                <?php echo ($_GET['act'] == "new") ? "Nueva Area" : "Editar Area"; ?>
            </h2>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12"><br></div>
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
            <div class="col-12">
                <div class="container">
                    <form action="" method="post" enctype="multipart/form-data">
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
                                    echo $tagInputRadioButtomNo;
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4"></div>
                            <div class="col-4">
                                <?php echo $tagButtomEditar;?>
                            </div>
                            <div class="col-4">
                                <?php echo $tagButtomGuardar;?>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/main.js"></script>
</body>

</html>