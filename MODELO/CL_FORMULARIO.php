<?php
class CL_FORMULARIO
{
    private $id_formulario;
    private $nombre;
    private $descripcion;
    private $fecha_creacion;
    private $fecha_limite;
    private $sucursal_id;
    private $departamentoId;
    private $puestoId;

    public function set_id_formulario($x)
    {
        $this->id_formulario = $x;
    }
    public function set_nombre($x)
    {
        $this->nombre = $x;
    }
    public function set_descripcion($x)
    {
        $this->descripcion = $x;
    }
    public function set_fecha_creacion($x)
    {
        $this->fecha_creacion = $x;
    }
    public function set_fecha_limite($x)
    {
        $this->fecha_limite = $x;
    }

    public function set_sucursal_id($x){
        $this->sucursal_id = $x;
    }

    public function set_departamentoId($x){
        $this->departamentoId = $x;
    }

    public function set_puestoId($x){
        $this->puestoId = $x;
    }
    public function get_id_formulario()
    {
        return $this->id_formulario;
    }
    public function get_nombre()
    {
        return $this->nombre;
    }
    public function get_descripcion()
    {
        return $this->descripcion;
    }
    public function get_fecha_creacion()
    {
        return $this->fecha_creacion;
    }
    public function get_fecha_limite()
    {
        return $this->fecha_limite;
    }

    public function get_sucursal_id(){
        return $this->sucursal_id;
    }

    public function get_departamentoId(){
        return $this->departamentoId;
    }

    public function get_puestoId(){
        return $this->puestoId;
    }

}
?>