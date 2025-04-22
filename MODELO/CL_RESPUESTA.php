<?php
class CL_RESPUESTA
{
    private $id_respuesta;
    private $respuesta_texto;
    private $respuesta_booleana;
    private $respuesta_opcion;
    private $fecha_respuesta;

    public function set_id_respuesta($x)
    {
        $this->id_respuesta = $x;
    }
    public function set_respuesta_texto($x)
    {
        $this->respuesta_texto = $x;
    }
    public function set_respuesta_booleana($x)
    {
        $this->respuesta_booleana = $x;
    }
    public function set_respuesta_opcion($x)
    {
        $this->respuesta_opcion = $x;
    }
    public function set_fecha_respuesta($x)
    {
        $this->fecha_respuesta = $x;
    }
    public function get_id_respuesta()
    {
        return $this->id_respuesta;
    }
    public function get_respuesta_texto()
    {
        return $this->respuesta_texto;
    }
    public function get_respuesta_booleana()
    {
        return $this->respuesta_booleana;
    }
    public function get_respuesta_opcion()
    {
        return $this->respuesta_opcion;
    }
    public function get_fecha_respuesta()
    {
        return $this->fecha_respuesta;
    }
}
?>