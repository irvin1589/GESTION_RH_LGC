<?php
class CL_DEPARTAMENTO
{
    private $id_departamento;
    private $nombre;

    public function set_id_departamento($x)
    {
        $this->id_departamento = $x;
    }

    public function set_nombre($x)
    {
        $this->nombre = $x;
    }

    public function get_id_departamento()
    {
        return $this->id_departamento;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }
}
?>