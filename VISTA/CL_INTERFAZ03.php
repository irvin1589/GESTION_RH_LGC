
<?php
include_once('../MODELO/CL_TABLA_DEPARTAMENTO.php');
include_once('../MODELO/CL_TABLA_PUESTO.php');
include_once('../MODELO/CL_TABLA_SUCURSAL.php');

class CL_INTERFAZ03
{
    private $interfaz;
    private $caja_texto1;
    private $caja_texto2;
    private $caja_texto3;
    private $caja_texto4;
    private $caja_texto5;
    private $caja_texto6;
    private $caja_texto7;
    private $caja_opcion1;
    private $caja_opcion2;
    private $caja_opcion3;
    private $caja_opcion4;

    // Elimina la versión sin parámetros de 'mostrar()'
    // public function mostrar()
    // {
    //     $this->interfaz = file_get_contents('../HTML/form_03.html');
    //     echo $this->interfaz;
    // }

    public function set_caja_texto1($x) { $this->caja_texto1 = $x; }
    public function set_caja_texto2($x) { $this->caja_texto2 = $x; }
    public function set_caja_texto3($x) { $this->caja_texto3 = $x; }
    public function set_caja_texto4($x) { $this->caja_texto4 = $x; }
    public function set_caja_texto5($x) { $this->caja_texto5 = $x; }
    public function set_caja_texto6($x) { $this->caja_texto6 = $x; }
    public function set_caja_texto7($x) { $this->caja_texto7 = $x; }
    public function set_caja_opcion1($x) { $this->caja_opcion1 = $x; }
    public function set_caja_opcion2($x) { $this->caja_opcion2 = $x; }
    public function set_caja_opcion3($x) { $this->caja_opcion3 = $x; }
    public function set_caja_opcion4($x) { $this->caja_opcion4 = $x; }

    public function get_caja_texto1() { return $_POST['caja_texto1']; }
    public function get_caja_texto2() { return $_POST['caja_texto2']; }
    public function get_caja_texto3() { return $_POST['caja_texto3']; }
    public function get_caja_texto4() { return $_POST['caja_texto4']; }
    public function get_caja_texto5() { return $_POST['caja_texto5']; }
    public function get_caja_texto6() { return $_POST['caja_texto6']; }
    public function get_caja_texto7() { return $_POST['caja_texto7']; }
    public function get_caja_opcion1() { return $_POST['caja_opcion1']; }
    public function get_caja_opcion2() { return $_POST['caja_opcion2']; }
    public function get_caja_opcion3() { return $_POST['caja_opcion3']; }
    public function get_caja_opcion4() { return $_POST['caja_opcion4']; }

    // Esta es la versión correcta de la función mostrar
    public function mostrar($selectedSucursal = '', $selectedDepartamento = '', $selectedPuesto = '')
    {
        $sucursalTabla = new CL_TABLA_SUCURSAL();
        $departamentoTabla = new CL_TABLA_DEPARTAMENTO();
        $puestoTabla = new CL_TABLA_PUESTO();

        $html = file_get_contents('../HTML/form_03.php');

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
}
