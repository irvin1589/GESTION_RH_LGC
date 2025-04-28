<?php
include_once('../MODELO/CL_TABLA_DEPARTAMENTO.php');
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

class CL_INTERFAZ16
{
    private $interfaz;
    private $caja_opcion1;
    private $caja_opcion2;

    public function set_caja_opcion1($x) { $this->caja_opcion1 = $x; }
    public function set_caja_opcion2($x) { $this->caja_opcion2 = $x; }
   
    public function get_caja_opcion1() { return isset($_POST['caja_opcion1']) ? intval($_POST['caja_opcion1']) : 0; }
    public function get_caja_opcion2() { return isset($_POST['caja_opcion2']) ? intval($_POST['caja_opcion2']) : 0; }

    
    public function mostrar($selectedSucursal = '', $selectedDepartamento = '')
    {
        $sucursalTabla = new CL_TABLA_SUCURSAL();
        $departamentoTabla = new CL_TABLA_DEPARTAMENTO();

        $html = file_get_contents('../HTML/form_16.html');

      
        $html_sucursales = $sucursalTabla->listar_sucursales($selectedSucursal);
        $html_departamentos = !empty($selectedSucursal) ? $departamentoTabla->listar_departamentos($selectedSucursal, $selectedDepartamento) : '<option value="">Seleccione un departamento</option>';
        
        $html = str_replace('{{sucursales}}', $html_sucursales, $html);
        $html = str_replace('{{departamentos}}', $html_departamentos, $html);

        echo $html;
    }
}
?>
