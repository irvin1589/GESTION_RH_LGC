<?php
 class CL_TIPO_PREGUNTA
 {
    private $id_tipo_pregunta;
    private $nombre_tipo;
    

    public function set_id_tipo_pregunta($x)
    {
        $this->id_tipo_pregunta = $x;
    }
    public function set_nombre_tipo($x)
    {
        $this->nombre_tipo = $x;
    }
    public function get_id_tipo_pregunta()
    {
        return $this->id_tipo_pregunta;
    }
    public function get_nombre_tipo()
    {
        return $this->get_nombre_tipo;
    }
 }
?>