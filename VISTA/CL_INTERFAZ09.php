<?php
    class CL_INTERFAZ09
    {
        private $interfaz;

        public function mostrar()
        {
            $this->interfaz=file_get_contents('../HTML/form_09.html');
            echo $this->interfaz;
        }


    }