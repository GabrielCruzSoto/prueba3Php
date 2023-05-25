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
    $conn = null;
    $listAreas = null;
    try {
        $sql = "SELECT cod_area, nom_area, descripcion, imagen, estado FROM areas;";
        $conn = conectarBD();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $listAreas = convertResultToListAreas($result);
        $result->free_result(); 
    } catch (Exception $e) {
        throw $e;
    }finally{
        cerrarBD($conn);
    }
    return $listAreas;
}

function getAreaById(int $code): Area
{
    $conn = null;
    $area = null;
    try {
        $sql = "SELECT cod_area, nom_area, descripcion, imagen, estado FROM areas WHERE cod_area = ?;";
        $conn = conectarBD();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if (($result-> num_rows) >= 1) {
            
            $listArea = convertResultToListAreas($result);
            $area = $listArea[0];
            $result->free_result(); 
        }else{
            throw new Exception("Not Found area  cod by ".$code);
        } 
    } catch (Exception $e) {
        throw $e;
    }finally{
        cerrarBD($conn);
    }
    return $area;
}
function saveArea(Area $area)
{
    $numrow = 0;
    $conn = null;
    try {
        $sql = "INSERT INTO areas (nom_area, descripcion, imagen, estado) VALUES (?,?,?,?)";
        $conn = conectarBD();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $area->getNameArea(), $area->getDescription(), $area->getImg(), $area->getStatus());
        $stmt->execute();
        $numrow = $stmt->affected_rows;

    } catch (Exception $e) {
        throw $e;
    }finally{
        cerrarBD($conn);
    }
    return $numrow;
}
function getByNombreAreaAndDescripcionAndImagenAndEstado(Area $area): Area
{
    $areaout = null;
    $conn = null;
    try {
        $sql = "SELECT cod_area, nom_area, descripcion, imagen, estado From areas WHERE nom_area=? AND descripcion = ? AND imagen = ? AND estado = ?";
        $conn = conectarBD();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $area->getNameArea(), $area->getDescription(), $area->getImg(), $area->getStatus());
        $stmt->execute();
        $result = $stmt->get_result();
        $listArea = convertResultToListAreas($result);
        $result->free_result(); 
        $areaout = $listArea[0];
    } catch (Exception $e) {
        throw $e;
    }finally{
        cerrarBD($conn);
    }
    return $areaout;
}

function deleteById(int $code): int
{
    $numrow = 0;
    $conn = null;
    try {
        $sql = "DELETE  FROM areas WHERE cod_area = ?;";
        $conn = conectarBD();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $code);
        $stmt->execute();
        $numrow = $stmt->affected_rows;
    } catch (Exception $e) {
        throw $e;
    }finally{
        cerrarBD($conn);
    }
    return $numrow;
}
