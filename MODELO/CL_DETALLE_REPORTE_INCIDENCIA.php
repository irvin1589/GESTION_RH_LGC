<?php
class CL_DETALLE_REPORTE_INCIDENCIA
{
    private $id_detalle;
    private $fecha_incidencia;

    public function set_id_detalle($x)
    {
        $this->id_detalle = $x;
    }
    public function set_fecha_incidencia($x)
    {
        $this->fecha_incidencia = $x;
    }
    public function get_id_detalle()
    {
        return $this->id_detalle;
    }
    public function get_fecha_incidencia()
    {
        return $this->fecha_incidencia;
    }
}