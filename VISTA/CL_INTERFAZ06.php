<?php
class CL_INTERFAZ06
{
    private $caja_texto1;
    private $caja_opcion1;
    private $interfaz;

    public function set_caja_texto1($x)
    {
        $this->caja_texto1 = $x;         
    }
    public function set_caja_opcion1($x)
    {
        $this->caja_opcion1 = $x; 
    }
    public function get_caja_texto1()
    {
        $this->caja_texto1=$_POST['caja_texto1'];
        return $this->caja_texto1;
    }
    public function get_caja_opcion1()
    { 
        $this->caja_opcion1=$_POST['caja_opcion1'];
        return $this->caja_opcion1;
    }

    public function mostrar1($selectedSucursal = '', $selectedDepartamento = '', $selectedPuesto = '')
    {
        $sucursalTabla = new CL_TABLA_SUCURSAL();
        $departamentoTabla = new CL_TABLA_DEPARTAMENTO();
        $puestoTabla = new CL_TABLA_PUESTO();

        $html = file_get_contents('../HTML/form_04.php');

        // Obtener listas dinámicas
        $html_sucursales = $sucursalTabla->listar_sucursales($selectedSucursal);
        $html_departamentos = !empty($selectedSucursal) ? $departamentoTabla->listar_departamentos($selectedSucursal, $selectedDepartamento) : '<option value="">Seleccione un departamento</option>';
        $html_puestos = (!empty($selectedSucursal) && !empty($selectedDepartamento)) ? $puestoTabla->listar_puestos($selectedSucursal, $selectedDepartamento, $selectedPuesto) : '<option value="">Seleccione un puesto</option>';

        // Reemplazar en la plantilla
        $html = str_replace('{{sucursales}}', $html_sucursales, $html);
        $html = str_replace('{{departamentos}}', $html_departamentos, $html);
        $html = str_replace('{{puestos}}', $html_puestos, $html);

        echo $html;
    }

    public function mostrar()
    {
        $this->interfaz=file_get_contents('../HTML/form_06.php');
        echo $this->interfaz;
    }
}