<?php
class CL_FORMULARIO
{
    private $id_formulario;
    private $nombre;
    private $descripcion;
    private $fecha_creacion;
    private $fecha_limite;

    public function set_id_formulario($x)
    {
        $this->id_formulario = $x;
    }
    public function set_nombre($x)
    {
        $this->nombre = $x;
    }
    public function set_descripcion($x)
    {
        $this->descripcion = $x;
    }
    public function set_fecha_creacion($x)
    {
        $this->fecha_creacion = $x;
    }
    public function set_fecha_limite($x)
    {
        $this->fecha_limite = $x;
    }
    public function get_id_formulario()
    {
        return $this->id_formulario;
    }
    public function get_nombre()
    {
        return $this->nombre;
    }
    public function get_descripcion()
    {
        return $this->descripcion;
    }
    public function get_fecha_creacion()
    {
        return $this->fecha_creacion;
    }
    public function get_fecha_limite()
    {
        return $this->fecha_limite;
    }

}
?>