CL_TABLA_USUARIO.php
<?php
include_once('../MODELO/CL_CONEXION.php'); // Incluir configuración de la base de datos

class CL_TABLA_USUARIO extends CL_CONEXION {
    protected $pdo;

    // public function __construct() {
    //     // Configura la conexión a la base de datos
    //     $this->pdo = new PDO('mysql:host=localhost;dbname=gestion_rh_lgc', 'root', '1234');
    // }

    // Método para guardar usuario en la base de datos
    public function guardar($usuario, $sucursal, $puesto, $departamento) {  
        // Obtener la conexión a través del método getPDO()
        $pdo = $this->getPDO(); 

        // Obtener los valores de los métodos de los objetos
        $id_usuario = $usuario->get_id_usuario();
        $nombre = $usuario->get_nombre();
        $apellido1 = $usuario->get_apellido1();
        $apellido2 = $usuario->get_apellido2();
        $contraseña = $usuario->get_contraseña();
        $tipo_usuario = $usuario->get_tipo_usuario();
        $sueldo_diario = $usuario->get_sueldo_diario();
        
        $id_sucursal = $sucursal->get_id_sucursal();
        $id_puesto = $puesto->get_id_puesto();
        $id_departamento = $departamento->get_id_departamento();

        // Sentencia SQL para insertar el usuario
        $sql = "INSERT INTO usuario (id_usuario, nombre, apellido1, apellido2, contraseña, id_sucursal, id_puesto, id_departamento, tipo_usuario, sueldo_diario) 
                VALUES (:id_usuario, :nombre, :apellido1, :apellido2, :contrasena, :id_sucursal, :id_puesto, :id_departamento, :tipo_usuario, :sueldo_diario)";
        
        // Preparar la consulta
        $stmt = $pdo->prepare($sql);

        // Asignar los valores a los parámetros
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido1', $apellido1);
        $stmt->bindParam(':apellido2', $apellido2);
        $stmt->bindParam(':contrasena', $contraseña); // Cambiado de :contraseña a :contrasena
        $stmt->bindParam(':id_sucursal', $id_sucursal);
        $stmt->bindParam(':id_puesto', $id_puesto);
        $stmt->bindParam(':id_departamento', $id_departamento);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);
        $stmt->bindParam(':sueldo_diario', $sueldo_diario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; // Registro exitoso
        } else {
            return false; // Error al registrar
        }
    }

    public function verificar_usuario($id_usuario, $contraseña) {
        $pdo = $this->getPDO();

        // Consulta SQL para verificar el usuario y obtener su tipo
        $sql = "SELECT tipo_usuario FROM usuario WHERE id_usuario = :id_usuario AND contraseña = :contrasena";
        $stmt = $pdo->prepare($sql);

        // Asignar los valores a los parámetros
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $contraseña, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el tipo de usuario si existe
        $tipo_usuario = $stmt->fetchColumn();

        // Si no se encontró el usuario, devolver false
        if (!$tipo_usuario) {
            return false;
        }

        // Devolver el tipo de usuario
        return $tipo_usuario;
    }

    public function listar_usuarios($id_sucursal, $id_departamento, $id_puesto, $selectedUsuario = '')
    {
    $pdo = $this->getPDO();
    $sql = "SELECT * FROM usuario WHERE id_sucursal = :id_sucursal AND id_departamento = :id_departamento AND id_puesto = :id_puesto";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_STR);
    $stmt->bindParam(':id_departamento', $id_departamento, PDO::PARAM_STR);
    $stmt->bindParam(':id_puesto', $id_puesto, PDO::PARAM_STR);
    $stmt->execute();

    $html = "";
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    if (count($usuarios) === 1) {
        $selectedUsuario = $usuarios[0]['id_usuario'];
    }

    foreach ($usuarios as $row) {
        $id = $row['id_usuario'];
        $nombre = $row['nombre'];
        $apellido1 = $row['apellido1'];
        $apellido2 = $row['apellido2'];
        $selected = ($selectedUsuario == $id) ? 'selected' : '';
        $html .= "<option value='$id' $selected>$nombre $apellido1 $apellido2</option>";
    }
    if (empty($html)) {
        $html = "<option value=''>No hay usuarios disponibles</option>";
    }
    return $html;
    }   


    public function listar_usuarios_por_sucursal_departamento($id_sucursal, $id_departamento, $selectedUsuario = '') {
        $pdo = $this->getPDO();
        $sql = "SELECT id_usuario, nombre, apellido1, apellido2 FROM usuario WHERE id_sucursal = :id_sucursal AND id_departamento = :id_departamento";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_STR);
        $stmt->bindParam(':id_departamento', $id_departamento, PDO::PARAM_STR);
        $stmt->execute();

        $html = "";
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($usuarios) === 1) {
            $selectedUsuario = $usuarios[0]['id_usuario'];  

        }

        foreach ($usuarios as $row) {
            $id = $row['id_usuario'];
            $nombre = $row['nombre'];
            $apellido1 = $row['apellido1'];
            $apellido2 = $row['apellido2'];
            $selected = ($selectedUsuario == $id) ? 'selected' : '';
            $html .= "<option value='$id' $selected>$nombre $apellido1 $apellido2</option>";
        }
        if (empty($html)) {
            $html = "<option value=''>No hay usuarios disponibles</option>";
        }
        return $html;

    }
        

    public function listar_todos_los_usuarios_con_detalles() {
        try {
            $sql = "SELECT 
                        u.id_usuario, 
                        u.nombre AS nombre,
                        u.apellido1,
                        u.apellido2,
                        u.contraseña,
                        u.tipo_usuario,
                        d.nombre AS nombre_departamento,
                        s.nombre AS nombre_sucursal,
                        p.nombre AS nombre_puesto
                    FROM usuario u
                    JOIN departamento d ON u.id_departamento = d.id_departamento
                    JOIN sucursal s ON u.id_sucursal = s.id_sucursal
                    JOIN puesto p ON u.id_puesto = p.id_puesto";
            
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar todos los usuarios con detalles: " . $e->getMessage());
            return [];
        }
    }

    public function listar_todos_los_usuarios_con_detalles_orden() {
        try {
            $sql = "SELECT 
                        u.id_usuario, 
                        u.nombre AS nombre,
                        u.apellido1,
                        u.apellido2,
                        u.contraseña,
                        u.tipo_usuario,
                        d.nombre AS nombre_departamento,
                        s.nombre AS nombre_sucursal,
                        p.nombre AS nombre_puesto
                    FROM usuario u
                    JOIN departamento d ON u.id_departamento = d.id_departamento
                    JOIN sucursal s ON u.id_sucursal = s.id_sucursal
                    JOIN puesto p ON u.id_puesto = p.id_puesto
                    ORDER BY s.nombre ASC, u.nombre ASC";
            
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar todos los usuarios con detalles: " . $e->getMessage());
            return [];
        }
    }

    public function listar_usuarios_por_filtros($id_departamento, $id_sucursal, $id_puesto) {
        try {
            $sql = "SELECT id_usuario, nombre 
                    FROM usuario 
                    WHERE id_departamento = :id_departamento 
                      AND id_sucursal = :id_sucursal 
                      AND id_puesto = :id_puesto";
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->bindParam(':id_departamento', $id_departamento, PDO::PARAM_INT);
            $stmt->bindParam(':id_sucursal', $id_sucursal, PDO::PARAM_INT);
            $stmt->bindParam(':id_puesto', $id_puesto, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar usuarios por filtros: " . $e->getMessage());
            return [];
        }
    }

    public function eliminar_usuario($id_usuario) {
        try {
            $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
            $stmt = $this->getPDO()->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar el usuario: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar_usuario_completamente($id_usuario) {
    try {
        $pdo = $this->getPDO();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT id_asignacion FROM asignacion_formulario WHERE id_usuario = :id_usuario");
        $stmt->execute([':id_usuario' => $id_usuario]);
        $asignaciones = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($asignaciones)) {
            foreach ($asignaciones as $id_asignacion) {
                $pdo->prepare("DELETE FROM respuesta WHERE id_asignacion = :id_asignacion")->execute([':id_asignacion' => $id_asignacion]);
            }
        }

        $pdo->prepare("DELETE FROM asignacion_formulario WHERE id_usuario = :id_usuario")->execute([':id_usuario' => $id_usuario]);

        $pdo->prepare("DELETE FROM notificacion WHERE id_usuario = :id_usuario")->execute([':id_usuario' => $id_usuario]);

        $pdo->prepare("DELETE FROM detalle_incidencia WHERE id_usuario = :id_usuario")->execute([':id_usuario' => $id_usuario]);

        $pdo->prepare("DELETE FROM usuario WHERE id_usuario = :id_usuario")->execute([':id_usuario' => $id_usuario]);

        $pdo->commit();
        return true;

    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error al eliminar completamente el usuario: " . $e->getMessage());
        return false;
    }
}


    public function editar_usuario($id_usuario, $nombre, $apellido1, $apellido2, $contraseña, $tipo_usuario, $sueldo_diario) {
    $pdo = $this->getPDO();
    $sql = "UPDATE usuario SET nombre = :nombre, apellido1 = :apellido1, apellido2 = :apellido2, contraseña = :contrasena, tipo_usuario = :tipo_usuario, sueldo_diario = :sueldo_diario WHERE id_usuario = :id_usuario";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido1', $apellido1);
    $stmt->bindParam(':apellido2', $apellido2);
    $stmt->bindParam(':contrasena', $contraseña);
    $stmt->bindParam(':tipo_usuario', $tipo_usuario);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':sueldo_diario', $sueldo_diario);

    return $stmt->execute();
}



    public function buscar_usuario_por_id($id_usuario) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :id_usuario";
        $stmt = $this->getPDO()->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>