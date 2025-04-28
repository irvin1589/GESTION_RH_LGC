<?php
class CL_INCIDENCIA_TIPO
{
    private $id_incidencia_tipo;
    private $descripcion;
    private $descuento;
    private $calculo_variable;

    public function set_id_incidencia_tipo($x)
    {
        $this->id_incidencia_tipo = $x;
    }
    public function set_descripcion($x)
    {
        $this->descripcion = $x;
    }
    public function set_descuento($x)
    {
        $this->descuento = $x;
    }
    public function set_calculo_variable($x)
    {
        $this->calculo_variable = $x;
    }
    public function get_id_incidencia_tipo()
    {
        return $this->id_incidencia_tipo;
    }
    public function get_descripcion()
    {
        return $this->descripcion;
    }
    public function get_descuento()
    {
        return $this->descuento;
    }
    public function get_calculo_variable()
    {
        return $this->calculo_variable;
    }
}
