<?php
    class CL_INTERFAZ14
    {
        private $interfaz;

        public function mostrar()
        {
            $this->interfaz=file_get_contents('../HTML/form_14.html');
            echo $this->interfaz;
        }


    }