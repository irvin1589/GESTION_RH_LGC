<?php
    class CL_INTERFAZ13
    {
        private $interfaz;

        public function mostrar()
        {
            $this->interfaz=file_get_contents('../HTML/form_13.html');
            echo $this->interfaz;
        }


    }