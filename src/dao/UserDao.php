<?php
    declare(strict_types=1);
    require('../src/config/ConexionDB.php');
    require('../src/model/User.php');
    require('../src/dao/mapper/UserMapper.php');
    require('../src/excepciones/UserException.php');


    function getUserByName(string $name):User {
        $user = null;
        $conn = null;
        try{
            $sql = "SELECT id, usuario, nombre, estado FROM usuarios WHERE usuario = ?";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();
            $list_users = convertResultToListUsers($result); 
            if(count($list_users)==0){
                cerrarBD2($conn,$result);
                throw new UserException("El usuario $name no existe");
            }
            if(count($list_users)>1){
                cerrarBD2($conn,$result);
                throw new UserException("Existen 2 usuarios con el mismo nombre $name");
            }
            $user = $list_users[0];
        }catch(UserException $ue){
            cerrarBD($conn);
            throw $ue;
        }
        return $user;
    }
    function getUserByIdAndPassword(int $id, string $pwd):void {
        $conn = null;
        try{
            $sql = "SELECT id, usuario, nombre, estado FROM usuarios WHERE id = ? AND clave = ?";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $id,$pwd);
            $stmt->execute();
            $result = $stmt->get_result();
            $list_users = convertResultToListUsers($result); 
            if(count($list_users)==0){
                cerrarBD2($conn,$result);
                throw new UserException("Clave invalida");
            }
            if(count($list_users)>1){
                cerrarBD2($conn,$result);
                throw new UserException("Existen 2 usuarios con el mismo Id ");
            }
        }catch(UserException $ue){
            cerrarBD($conn);
            throw $ue;
        }
    }
    function existUserByName(string $name): bool{
        $isExist = false;
        $conn = null;
        try{
            $sql = "SELECT id, usuario, estado FROM usuarios WHERE usuario = ? ;";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            
            $isExist = ($stmt->num_rows()>=1);
            cerrarBD($conn);
        }catch(Exception $e){
            cerrarBD($conn);
            throw $e;

        }
        return $isExist;
    }
    function saveUser(User $user){
        $conn= null;
        $numRow = 0;
        try{
            $sql = "INSERT INTO usuarios (usuario, nombre, clave, estado) values (?,?,?,?);";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", ($user->getName()),$user->getFullName(),$user->getPwd(),$user->getStatus());
            $stmt->execute();
            $numRow = $stmt->affected_rows;
            cerrarBD($conn);
        }catch(Exception $e){
            cerrarBD($conn);
            throw $e;
        }
        return $numRow;
    }
    function getAllUsers(){
        $listUsers=array();
        $conn=null;
        try{
            $sql = "SELECT id, usuario, nombre ,estado FROM usuarios ;";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $listUsers = convertResultToListUsers($result);
            cerrarBD2($conn,$result);
        }catch(Exception $e){
            cerrarBD($conn);
            
        }
        return  $listUsers;
    }

?>