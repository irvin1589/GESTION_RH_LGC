<?php

class CL_PERIODO_REPORTE
{
    private $id_periodo;
    private $fecha_inicio;
    private $fecha_fin;

    public function set_id_periodo($x)
    {
        $this->id_periodo = $x;
    }

    public function set_fecha_inicio($x)
    {
        $this->fecha_inicio = $x;
    }
    public function set_fecha_fin($x)
    {
        $this->fecha_fin = $x;
    }
    public function get_id_periodo()
    {
        return $this->id_periodo;
    }
    public function get_fecha_inicio()
    {
        return $this->fecha_inicio;
    }
    public function get_fecha_fin()
    {
        return $this->fecha_fin;
    }
}