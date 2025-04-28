<?php
class CL_INCIDENCIA
{
    private $codigo;
    private $descripcion;
    private $tipo;

    public function set_codigo($x)
    {
        $this->codigo = $x;
    }
    public function set_descripcion($x)
    {
        $this->descripcion = $x;
    }
    public function set_tipo($x)
    {
        $this->tipo = $x;
    }
    public function get_codigo()
    {
        return $this->codigo;
    }
    public function get_descripcion()
    {
        return $this->descripcion;
    }
    public function get_tipo()
    {
        return $this->tipo;
    }
}