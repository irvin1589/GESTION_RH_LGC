<?php
    class CL_INTERFAZ01
    {
        private $interfaz;
        private $caja_texto1;
        private $caja_texto2;
        
        public function mostrar()
        {
            $this->interfaz=file_get_contents('../HTML/form_01.html');
            echo $this->interfaz;
        }

        public function set_caja_texto1($x)
        {
            $this->caja_texto1 = $x;         
        }

        public function set_caja_texto2($x)
        {
            $this->caja_texto2 = $x;         
        }
        public function get_caja_texto1()
        {
            $this->caja_texto1=$_POST['caja_texto1'];
            return $this->caja_texto1;
        }
        public function get_caja_texto2()
        {
            $this->caja_texto2=$_POST['caja_texto2'];
            return $this->caja_texto2;
        }

    }
?>