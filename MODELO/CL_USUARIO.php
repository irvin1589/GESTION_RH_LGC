<?php
class CL_USUARIO
{
    private $id_usuario;
    private $nombre;
    private $apellido1;
    private $apellido2;
    private $contraseña;
    private $tipo_usuario; // 1: Alumno, 2: Profesor, 3: Administrador

    public function set_id_usuario($x)
    {
        $this->id_usuario = $x;
    }

    public function set_nombre($x)
    {
        $this->nombre = $x;
    }
    public function set_apellido1($x)
    {
        $this->apellido1 = $x;
    }
    public function set_apellido2($x)
    {
        $this->apellido2 = $x;
    }
    public function set_contraseña($x)
    {
        $this->contraseña = $x;
    }
    public function set_tipo_usuario($x)
    {
        $this->tipo_usuario = $x;
    }
    public function get_id_usuario()
    {
        return $this->id_usuario;
    }
    public function get_nombre()
    {
        return $this->nombre;
    }
    public function get_apellido1()
    {
        return $this->apellido1;
    }
    public function get_apellido2()
    {
        return $this->apellido2;
    }
    public function get_contraseña()
    {
        return $this->contraseña;
    }
    public function get_tipo_usuario()
    {
        return $this->tipo_usuario;
    }
		
}
?>