<?php

require_once __DIR__ . '/../models/UsuarioModel.php';
require_once __DIR__ . '/AuthMiddleware.php';

class AdminEmpleadoController
{
    // MOSTRAR FORMULARIO DE REGISTRO
    public function index()
    {
        AuthMiddleware::soloAdmin();

        require_once __DIR__ . '/../views/admin/empleados/registrar.php';
    }

    public function registrar(){
        // SOLO EL ADMINISTRADOR
        AuthMiddleware::soloAdmin();

        //VALIDAR METODO POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/admin/empleado/registrar');
            exit;
        }
        // RECUPERAR DATOS
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmarPassword = $_POST['confirmarPassword'] ?? '';
        $confirmarConsentimiento = $_POST['confirmarConsentimiento'] ?? '';
        // ========================================================================
        // VALIDAR CAMPOS OBLIGATORIOS
        if (
            $nombre === '' ||
            $correo === '' ||
            $telefono === '' ||
            $password === '' ||
            $confirmarPassword === ''
        ) {
            $_SESSION['mensaje_empleado'] = 'Todos los campos son obligatorios.';
            header('Location: /PJF/admin/empleado/registrar');
            exit;
        }

        // VALIDAR CORREO
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensaje_empleado'] = 'Ingrese un correo válido.';
            header('Location: /PJF/admin/empleado/registrar');
            exit;
        }
        // ========================================================================
        // VALIDAR LONGITUDES
        if (
            mb_strlen($nombre) > 100 ||
            strlen($correo) > 150 ||
            !preg_match('/^[0-9]{10}$/', $telefono)
        ) {
            $_SESSION['mensaje_empleado'] = 'Verifique el nombre, correo y teléfono.';
            header('Location: /PJF/admin/empleado/registrar');
            exit;
        }
        // ========================================================================
        // VALIDAR CONTRASEÑAS
        if ($password !== $confirmarPassword) {
            $_SESSION['mensaje_empleado'] = 'Las contraseñas no coinciden.';
            header('Location: /PJF/admin/empleado/registrar');
            exit;
        }
        if (strlen($password) < 8) {
            $_SESSION['mensaje_empleado'] = 'La contraseña debe tener mínimo 8 caracteres.';
            header('Location: /PJF/admin/empleado/registrar');
            exit;
        }
        // ========================================================================
        // CONFIRMAR AUTORIZACIÓN DE DATOS
        if ($confirmarConsentimiento !== '1') {
            $_SESSION['mensaje_empleado'] = 'Debe confirmar la autorización del empleado.';
            header('Location: /PJF/admin/empleado/registrar');
            exit;
        }
        // =========================================================================
        
        // ==========================================================================
        // REGISTRAR EMPLEADO
        try {
            
            // CONSULTAR SI EXISTE EL CORREO
            $model = new UsuarioModel();

            if ($model->buscarPorCorreo($correo)) {
                $_SESSION['mensaje_empleado'] = 'El correo ya está registrado.';
                header('Location: /PJF/admin/empleado/registrar');
                exit;
            }
            $versionPolitica = '1.0';

            $model->registrarEmpleado(
                $nombre,
                $correo,
                $telefono,
                $password,
                $versionPolitica
            );

            $_SESSION['mensaje_empleado_exito'] = 'Empleado registrado correctamente.';

            header('Location: /PJF/admin/empleado/registrar');
            exit;

        } catch (PDOException $e) {

            error_log($e->getMessage());

            $_SESSION['mensaje_empleado'] = 'No fue posible registrar al empleado.';

            header('Location: /PJF/admin/empleado/registrar');
            exit;
        }
    }
    
}