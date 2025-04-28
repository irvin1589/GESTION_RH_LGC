<?php

class CL_DETALLE_INCIDENCIA
{

    private $id_detalle_incidencia;
    private $cantidad;
    private $descuento;
    private $fecha_inicio;
    private $fecha_termino;

    public function set_id_detalle_incidencia($x)
    {
        $this->id_detalle_incidencia = $x;
    }
    public function set_cantidad($x)
    {
        $this->cantidad = $x;
    }
    public function set_descuento($x)
    {
        $this->descuento = $x;
    }
    public function set_fecha_inicio($x)
    {
        $this->fecha_inicio = $x;
    }
    public function set_fecha_termino($x)
    {
        $this->fecha_termino = $x;
    }
    public function get_id_detalle_incidencia()
    {
        return $this->id_detalle_incidencia;
    }
    public function get_cantidad()
    {
        return $this->cantidad;
    }
    public function get_descuento()
    {
        return $this->descuento;
    }
    public function get_fecha_inicio()
    {
        return $this->fecha_inicio;
    }
    public function get_fecha_termino()
    {
        return $this->fecha_termino;
    }
}