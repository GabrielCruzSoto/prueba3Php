<?php 
    class UserException extends Exception{
        public function __construct($mensaje = "", $codigo = 100, Exception $excepcionAnterior = null) {
            parent::__construct($mensaje, $codigo, $excepcionAnterior);
        }
    
        public function __toString() {
            return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
        }
    }

?>