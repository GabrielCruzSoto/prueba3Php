<?php
    declare(strict_types=1);
    require('../src/config/ConexionDB.php');
    require('../src/model/User.php');
    require('../src/dao/mapper/UserMapper.php');
    require('../src/excepciones/UserException.php');


    function getUserByName(string $name): User {
        $user = null;
        $conn = null;
        
        try {
            $sql = "SELECT id, usuario, nombre, estado FROM usuarios WHERE usuario = ?";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $list_users = convertResultToListUsers($result);
            
            if (count($list_users) === 0) {
                throw new UserException("El usuario $name no existe");
            }
            
            if (count($list_users) > 1) {
                throw new UserException("Existen 2 usuarios con el mismo nombre $name");
            }
            
            $user = $list_users[0];
            
            $result->free_result();
        } catch (UserException $ue) {
            throw $ue;
        } catch (Exception $e) {
            throw $e;
        } finally {
            cerrarBD($conn);
        }
        
        return $user;
    }
    function existUserByIdAndPassword(int $id, string $pwd):bool {
        $conn = null;
        $isExist= false;
        try{
            $sql = "SELECT id, usuario, nombre, estado FROM usuarios WHERE id = ? AND clave = ?";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $id,$pwd);
            $stmt->execute();
            $result = $stmt->get_result();
            $list_users = convertResultToListUsers($result); 
            $result->free_result(); 
            if(count($list_users)==1){
                $isExist= true;
            }
        } catch (UserException $ue) {
            throw $ue;
        } catch (Exception $e) {
            throw $e;
        } finally {
            cerrarBD($conn);
        }
        return $isExist;
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
            $result->free_result(); 
            if(count($list_users)==0){
                throw new UserException("Clave invalida");
            }
            if(count($list_users)>1){
                throw new UserException("Existen 2 usuarios con el mismo Id ");
            }
        } catch (UserException $ue) {
            throw $ue;
        } catch (Exception $e) {
            throw $e;
        } finally {
            cerrarBD($conn);
        }
        return;
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
        } catch (Exception $e) {
            throw $e;
        } finally {
            cerrarBD($conn);
        }
        return $isExist;
    }
    function saveUser(User $user) {
        $conn = null;
        $numRow = 0;
    
        try {
            $sql = "INSERT INTO usuarios (usuario, nombre, clave, estado) VALUES (?, ?, ?, ?)";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $user->getName(), $user->getFullName(), $user->getPwd(), $user->getStatus());
            $stmt->execute();
            $numRow = $stmt->affected_rows;
        } catch (Exception $e) {
            throw $e;
        } finally {
            cerrarBD($conn);
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
            $result->free_result(); 
        } catch (Exception $e) {
            throw $e;
        } finally {
            cerrarBD($conn);
        }
        return  $listUsers;
    }
    function deleteUserById(int $id){
        $conn=null;
        try{
            $sql = "DELETE FROM usuarios WHERE id = ? ;";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
        } catch (Exception $e) {
            throw $e;
        } finally {
            cerrarBD($conn);
        }
    }
    function getUserById(int $id): User{
        $conn=null;
        $listUsers=[];
        try{
            $sql = "SELECT id, usuario, nombre ,estado FROM usuarios WHERE id = ? ;";
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $listUsers = convertResultToListUsers($result);
            if (count($listUsers) === 0) {
                throw new UserException("El usuario con ".strval($id)." no existe");
            }
            
            if (count($listUsers) > 1) {
                throw new UserException("Existen 2 usuarios con el id ".strval($id));
            }
            $result->free_result(); 
        } catch (Exception $e) {
            throw $e;
        } finally {
            cerrarBD($conn);
        }
        return $listUsers[0];
    }

    function updateUser(User $user) {
        $conn = null;
        $numRow = 0;
    
        try {
            $sql = "UPDATE usuarios SET nombre=?, clave= ?, estado= ? WHERE id =?";
            if($user->getPwd()==""){
                $sql = "UPDATE usuarios SET nombre=?, estado= ? WHERE id =?";
            }
            $conn = conectarBD();
            $stmt = $conn->prepare($sql);
            
            if($user->getPwd()==""){
                $stmt->bind_param("sii",  $user->getFullName(), $user->getStatus(),$user->getId());
            }else{
                $stmt->bind_param("ssii",  $user->getFullName(), $user->getPwd(), $user->getStatus(),$user->getId());
            }
            
            $stmt->execute();
            $numRow = $stmt->affected_rows;
        } catch (Exception $e) {
            throw $e;
        } finally {
            cerrarBD($conn);
        }
    
        return $numRow;
    }
?>