<?php

    function convertResultToListAreas($result): array
    {
        $listAreas = array();
        if ($result->num_rows >0) {
            while($row = $result -> fetch_assoc()){
                $area = new Area();
                $area->setCode($row['cod_area']);
                $area->setNameArea($row['nom_area']);
                $area->setDescription($row['descripcion']);
                $area->setImg($row['imagen']);
                $area->setStatus($row['estado']);
                $listAreas[] = $area;
            }
            
        }
        return $listAreas;
        
    }
?>
