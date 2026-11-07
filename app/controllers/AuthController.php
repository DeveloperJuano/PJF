<?php

require_once __DIR__ . '/../models/UsuarioModel.php';

class AuthController
{
    public function mostrarLogin()
    {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/login');
            exit;
        }

        $correo   = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($correo === '' || $password === '') {
            $_SESSION['mensaje_login'] = 'Ingrese su correo y tu contraseña';
            header('Location: /PJF/login');
            exit;
        }

        $model = new UsuarioModel();
        $usuario = $model->buscarPorCorreo($correo);

        if (!$usuario || !password_verify($password, $usuario['password']) || $usuario['estado'] !== 'activo') {
            $_SESSION['mensaje_login'] = 'Correo o contraseña erroneos';
            header('Location: /PJF/login');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['idUsuario'] = $usuario['idUsuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        if ($usuario['rol'] === 'admin') {
            $_SESSION['mensaje_login'] = 'Login correcto. Redirigiendo al panel.';
            header('Location: /PJF/admin');
            exit;
        } else {
            header('Location: /PJF');
            exit;
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        header('Location: /PJF/login');
        exit;
    }
    // VERIFICACION DE MODELO
    public function registrar()
    {   
        //VALIDA SI ES POST
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: /PJF/registro');
            exit;
        }
        
        // RECESION DE DATOS
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        $password = $_POST['password'] ?? '';
        $confirmarPassword = $_POST['confirmarPassword'] ?? '';

        $aceptaTratamiento = $_POST['aceptaTratamiento'] ?? '';

        //VALIDAR CAMPOS 
        if (
        $nombre === '' ||
        $correo === '' ||
        $telefono === '' ||
        $password === '' ||
        $confirmarPassword === ''
        ) {
            $_SESSION['mensaje_registro'] = 'Todos los campos son obligatorios.';
            header('Location: /PJF/registro');
            exit;
        }
        // VALIDAR FORMATO DEL CORREO
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensaje_registro'] = 'Ingrese un correo electrónico válido.';
            header('Location: /PJF/registro');
            exit;
        }
        // LONGITUD DE LOS DATOS
        if (mb_strlen($nombre) > 100 || strlen($correo) > 150 || strlen($telefono) > 10) {
            $_SESSION['mensaje_registro'] = 'Uno de los campos supera la longitud permitida.';
            header('Location: /PJF/registro');
            exit;
        }
        // VALIDAR  QUE LAS CONTRASENAS CONCIDAN
        if ($password !== $confirmarPassword) {
            $_SESSION['mensaje_registro'] = 'Las contraseñas no coinciden.';
            header('Location: /PJF/registro');
            exit;
        }
        // VALIDAR LONGITUD DE CONTRASENA
        if (strlen($password) < 8) {
            $_SESSION['mensaje_registro'] = 'La contraseña debe tener al menos 8 caracteres.';
            header('Location: /PJF/registro');
            exit;
        }
        // VALIDAR TRAMIENTO DE  DATOS 
        if ($aceptaTratamiento !== '1') {
            $_SESSION['mensaje_registro'] = 'Debe aceptar el tratamiento de datos personales.';
            header('Location: /PJF/registro');
            exit;
        }
        // CONSULTAR DEL METODO BUCAR CORREO SI EXISTE EL INGRESADO
        $model = new  UsuarioModel();

        if ($model->buscarPorCorreo($correo)) {
            $_SESSION['mensaje_registro'] = 'Este correo ya se encuentra registrado.';
            header('Location: /PJF/registro');
            exit;
        }
        // SI PASA TODOS LOS FILTROS HACE EL INTENTE DE REGISTRAR  DATOS
        try{
                // POR SI SE VA ACTUALIZANDO LAS POLITICAS
                $versionPolitica = '1.0';

                $model->registrarUsuario(
                    $nombre,
                    $correo,
                    $telefono,
                    $password,
                    $versionPolitica
                );

                $_SESSION['mensaje_exito'] = 'Cuenta creada correctamente. Ya puedes iniciar sesión.';

                header('Location: /PJF/login');
                exit;

            } catch (PDOException $e) {

                error_log($e->getMessage());

                $_SESSION['mensaje_registro'] = 'No fue posible completar el registro. Inténtalo nuevamente.';

                header('Location: /PJF/registro');
                exit;
            }
        }
    }
