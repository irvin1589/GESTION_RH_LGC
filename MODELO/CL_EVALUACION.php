<?php
class CL_EVALUACION
{
    private $id_evaluacion;
    private $fecha;
    private $resultado;

    public function set_id_evaluacion($x)
    {
        $this->id_evaluacion = $x;
    }
    public function set_fecha($x)
    {
        $this->fecha = $x;
    }
    public function set_resultado($x)
    {
        $this->resultado = $x;
    }
    public function get_id_evaluacion()
    {
        return $this->id_evaluacion;
    }
    public function get_fecha()
    {
        return $this->fecha;
    }
    public function get_resultado()
    {
        return $this->resultado;
    }
}
?>