<?php
declare(strict_types=1);
require('../src/config/ConexionDB.php');
require('../src/model/Area.php');
require('../src/dao/mapper/AreaMapper.php');
/**
 * @throws Exception
 */
function getAllAreas(): array
{
    $sql = "SELECT cod_area, nom_area, descripcion, imagen, estado FROM areas;";
    $conn = conectarBD();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $listAreas= convertResultToListAreas($result);

    cerrarBD2($conn,$result);
    return $listAreas;
}

function getAreaById(int $code): Area
{
    $sql = "SELECT cod_area, nom_area, descripcion, imagen, estado FROM areas WHERE cod_area = ?;";
    $conn = conectarBD();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $listArea = convertResultToListAreas($result);
    cerrarBD2($conn,$result);
    return $listArea[0];
}

?>