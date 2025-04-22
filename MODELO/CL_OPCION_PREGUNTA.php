<?php
class CL_OPCION_PREGUNTA
{
    private $id_opcion;
    private $texto_opcion;
    private $valor_opcion;

    public function set_id_opcion($x)
    {
        $this->id_opcion = $x;
    }
    public function set_texto_opcion($x)
    {
        $this->texto_opcion = $x;
    }
    public function set_valor_opcion($x)
    {
        $this->valor_opcion = $x;
    }
    public function get_id_opcion()
    {
        return $this->id_opcion;
    }
    public function get_texto_opcion()
    {
        return $this->texto_opcion;
    }
    public function get_valor_opcion()
    {
        return $this->valor_opcion;
    }
}
?>