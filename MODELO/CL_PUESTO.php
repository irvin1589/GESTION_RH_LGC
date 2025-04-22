<?php
class CL_PUESTO
{
    private $id_puesto;
    private $nombre;

    public function set_id_puesto($x)
    {
        $this->id_puesto = $x;
    }

    public function set_nombre($x)
    {
        $this->nombre = $x;
    }
    public function get_id_puesto()
    {
        return $this->id_puesto;
    }
    public function get_nombre()
    {
        return $this->nombre;
    }
}
?>
