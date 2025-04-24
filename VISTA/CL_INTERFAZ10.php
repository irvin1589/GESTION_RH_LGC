<?php
    class CL_INTERFAZ10
    {
        private $interfaz;

        public function mostrar()
        {
            $this->interfaz=file_get_contents('../HTML/form_10.html');
            echo $this->interfaz;
        }


    }