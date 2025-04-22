<?php
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

class CL_INTERFAZ05
{
    private $interfaz;
    private $caja_texto1;
    private $caja_texto2;
    private $caja_texto3;
    private $caja_texto4;

    public function set_caja_texto1($x)
        {
            $this->caja_texto1 = $x;         
        }
    public function set_caja_texto2($x)
     { 
        $this->caja_texto2 = $x; 
    }
    public function set_caja_texto3($x)
     { 
        $this->caja_texto3 = $x; 
    }
    public function set_caja_texto4($x)
     { 
        $this->caja_texto4 = $x; 
    }

    public function get_caja_texto1()
    {
        $this->caja_texto1=$_POST['caja_texto1'];
        return $this->caja_texto1;
    }
    public function get_caja_texto2()
     { $this->caja_texto1=$_POST['caja_texto2'];
        return $this->caja_texto2;}

    public function get_caja_texto3()
        { $this->caja_texto1=$_POST['caja_texto3'];
            return $this->caja_texto3;}

    public function get_caja_texto4()
     { $this->caja_texto4=$_POST['caja_texto4'];
        return $this->caja_texto4; }

    public function mostrar()
        {
            $this->interfaz=file_get_contents('../HTML/form_05.html');
            echo $this->interfaz;
        }
    }
?>
