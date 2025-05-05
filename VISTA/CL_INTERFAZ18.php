<?php
    class CL_INTERFAZ18
    {
        private $interfaz;
        public function mostrar()
        {
            $this->interfaz=file_get_contents('../HTML/form18.html');
            echo $this->interfaz;
        }
    }
?>