<?php
class CL_REPORTE_INCIDENCIA
{
    private $id_reporte;
    private $fecha_reporte;

    public function set_id_reporte($x)
    {
        $this->id_reporte = $x;
    }
    public function set_fecha_reporte($x)
    {
        $this->fecha_reporte = $x;
    }
    public function get_id_reporte()
    {
        return $this->id_reporte;
    }
    public function get_fecha_reporte()
    {
        return $this->fecha_reporte;
    }
}