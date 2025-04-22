<?php
class CL_NOTIFICACION
{
    private $id_notificacion;
    private $mensaje;
    private $fecha;
    private $leida;
    private $tipo;

    public function set_id_notificacion($x)
    {
        $this->id_notificacion = $x;
    }
    public function set_mensaje($x)
    {
        $this->mensaje = $x;
    }
    public function set_fecha($x)
    {
        $this->fecha = $x;
    }
    public function set_leida($x)
    {
        $this->leida = $x;
    }
    public function set_tipo($x)
    {
        $this->tipo = $x;
    }
    public function get_id_notificacion()
    {
        return $this->id_notificacion;
    }
    public function get_mensaje()
    {
        return $this->mensaje;
    }
    public function get_fecha()
    {
        return $this->fecha;
    }
    public function get_leida()
    {
        return $this->leida;
    }
    public function get_tipo()
    {
        return $this->tipo;
    }
    
}
?>