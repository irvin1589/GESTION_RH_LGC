<?php
include_once('../MODELO/CL_TABLA_DEPARTAMENTO.php');
include_once('../MODELO/CL_TABLA_SUCURSAL.php');
include_once('../MODELO/CL_TABLA_INCIDENCIA_TIPO.php');
include_once('../MODELO/CL_TABLA_USUARIO.php');

class CL_INTERFAZ17
{
    private $interfaz;
    private $caja_opcion1;
    private $caja_opcion2;
    private $caja_opcion3;
    private $caja_opcion4;
    private $caja_texto1;

    public function set_caja_opcion1($x) { $this->caja_opcion1 = $x; }
    public function set_caja_opcion2($x) { $this->caja_opcion2 = $x; }
    public function set_caja_opcion3($x) { $this->caja_opcion3 = $x; }
    public function set_caja_opcion4($x) { $this->caja_opcion4 = $x; }
    public function set_caja_texto1($x) { $this->caja_texto1 = $x; }
   
    public function get_caja_opcion1() { return isset($_POST['caja_opcion1']) ? intval($_POST['caja_opcion1']) : 0; }
    public function get_caja_opcion2() { return isset($_POST['caja_opcion2']) ? intval($_POST['caja_opcion2']) : 0; }
    public function get_caja_opcion3() { return isset($_POST['caja_opcion3']) ? intval($_POST['caja_opcion3']) : 0; }
    public function get_caja_opcion4() { return isset($_POST['caja_opcion4']) ? intval($_POST['caja_opcion4']) : 0; }
    public function get_caja_texto1() { return isset($_POST['caja_texto1']) ? $_POST['caja_texto1'] : ''; }
    
    public function mostrar($selectedSucursal = '', $selectedDepartamento = '', $selectedUsuario = '', $selectedTipoIncidencia = '')
    {
        $sucursalTabla = new CL_TABLA_SUCURSAL();
        $departamentoTabla = new CL_TABLA_DEPARTAMENTO();
        $usuarioTabla = new CL_TABLA_USUARIO();
        $tipoIncidenciaTabla = new CL_TABLA_INCIDENCIA_TIPO();

        $html = file_get_contents('../HTML/form_17.html');

      
        $html_sucursales = $sucursalTabla->listar_sucursales($selectedSucursal);
        $html_departamentos = !empty($selectedSucursal) ? $departamentoTabla->listar_departamentos($selectedSucursal, $selectedDepartamento) : '<option value="">Seleccione un departamento</option>';
        
        $html_usuarios = !empty($selectedSucursal && $selectedDepartamento) ? $usuarioTabla->listar_usuarios_por_sucursal_departamento($selectedSucursal, $selectedDepartamento, $selectedUsuario) : '<option value="">Seleccione un usuario</option>';

        $html_tipo_incidencias = $tipoIncidenciaTabla->listar_tipos_incidencia($selectedTipoIncidencia); 


        $html = str_replace('{{sucursales}}', $html_sucursales, $html);
        $html = str_replace('{{departamentos}}', $html_departamentos, $html);
        $html = str_replace('{{usuarios}}', $html_usuarios, $html);
        $html = str_replace('{{tipos_incidencia}}', $html_tipo_incidencias, $html);
        echo $html;
    }
}
?>
