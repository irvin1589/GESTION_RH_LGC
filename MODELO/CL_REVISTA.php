<?php
class CL_REVISTA
{
    private $id_revista;
    private $titulo;
    private $contenido;
    private $fecha_publicacion;
    private $autor;
    private $archivo_pdf;

    public function set_id_revista($x)
    {
        $this->id_revista = $x;
    }
    public function set_titulo($x)
    {
        $this->titulo = $x;
    }
    public function set_contenido($x)
    {
        $this->contenido = $x;
    }
    public function set_fecha_publicacion($x)
    {
        $this->fecha_publicacion = $x;
    }
    public function set_autor($x)
    {
        $this->autor = $x;
    }
    public function set_archivo_pdf($x)
    {
        $this->archivo_pdf = $x;
    }
    public function get_id_revista()
    {
        return $this->id_revista;
    }
    public function get_titulo()
    {
        return $this->titulo;
    }
    public function get_contenido()
    {
        return $this->contenido;
    }
    public function get_fecha_publicacion()
    {
        return $this->fecha_publicacion;
    }
    public function get_autor()
    {
        return $this->autor;
    }
    public function get_archivo_pdf()
    {
        return $this->archivo_pdf;
    }
    
    

}