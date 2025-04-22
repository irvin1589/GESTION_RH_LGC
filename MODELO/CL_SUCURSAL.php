<?php
class CL_SUCURSAL
{
    private $id_sucursal;
    private $nombre;
    private $direccion;
    private $telefono;

    public function set_id_sucursal($x)
    {
        $this->id_sucursal = $x;
    }

    public function set_nombre($x)
    {
        $this->nombre = $x;
    }

    public function set_direccion($x)
    {
        $this->direccion = $x;
    }

    public function set_telefono($x)
    {
        $this->telefono = $x;
    }

    public function get_id_sucursal()
    {
        return $this->id_sucursal;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    public function get_direccion()
    {
        return $this->direccion;
    }

    public function get_telefono()
    {
        return $this->telefono;
    }
}
?>