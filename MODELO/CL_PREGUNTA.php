<?php
 class CL_PREGUNTA
 {
    private $id_pregunta;
    private $texto;
    private $orden;

    public function set_id_pregunta($x)
    {
        $this->id_pregunta = $x;
    }
    public function set_texto($x)
    {
        $this->texto = $x;
    }
    public function set_orden($x)
    {
        $this->orden = $x;
    }
    public function get_id_pregunta()
    {
        return $this->id_pregunta;
    }
    public function get_texto()
    {
        return $this->texto;
    }
    public function get_orden()
    {
        return $this->orden;
    }
 }
?>