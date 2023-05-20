<?php

    function convertResultToListUsers($result){
        $listUsers = array();
        if ($result->num_rows >=1) {
            while($row = $result -> fetch_assoc()){
                $user = new User();
                $user->setId($row['id']);
                $user->setName($row['usuario']);
                $user->setStatus($row['estado']);
                $listUsers[] = $user;
            }
            
        }
        return $listUsers;
        
    }
?>