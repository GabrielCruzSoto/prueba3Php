<?php
    declare(strict_types=1);
    require('../src/config/ConexionDB.php');
    require('../src/model/User.php');
    require('../src/dao/mapper/UserMapper.php');
    require('../src/excepciones/UserException.php');


    function getUserByName(string $name):User {
        $user = null;
        try{
            $sql = "SELECT id, usuario, estado FROM usuarios WHERE usuario = ?";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();
            $list_users = convertResultToListUsers($result); 
            if(count($list_users)==0){
                throw new UserException("El usuario $name no existe");
            }
            if(count($list_users)>1){
                throw new UserException("Existen 2 usuarios con el mismo nombre $name");
            }
            $user = $list_users[0];
        }catch(UserException $ue){
            throw $ue;
        }
        return $user;
    }
    function getUserByIdAndPassword(int $id, string $pwd):void {
        try{
            $sql = "SELECT id, usuario, estado FROM usuarios WHERE id = ? AND clave = ?";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $id,$pwd);
            $stmt->execute();
            $result = $stmt->get_result();
            $list_users = convertResultToListUsers($result); 
            if(count($list_users)==0){
                throw new UserException("Clave invalida");
            }
            if(count($list_users)>1){
                throw new UserException("Existen 2 usuarios con el mismo Id ");
            }
        }catch(UserException $ue){
            throw $ue;
        }
    }
?>