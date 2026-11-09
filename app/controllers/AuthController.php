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
            header('Location: /PJF');
            exit;
        }
        // ===================================================
        // AYUDA AL LOGIN MODAL A UTILIZAR Y REDIRIGIR A CUALQUIR MODULO 
        // DENTRO DE SUS RESTRICCION 
        // RECECION DEL INPUT 
        $paginaOrigen = $_POST['paginaOrigen'] ?? '/PJF';

        $paginasPermitidas = [
            '/PJF',
            '/PJF/',
            '/PJF/menu',
            '/PJF/reserva'
        ];

        // VALIDAR DESTINO
        if (!in_array($paginaOrigen, $paginasPermitidas, true)) {
            $paginaOrigen = '/PJF';
        }
        // ===================================================

        $correo   = trim($_POST['correo'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($correo === '' || $password === '') {
            $_SESSION['mensaje_login'] = 'Ingrese su correo y tu contraseña';
            header('Location: ' . $paginaOrigen);
            exit;
        }

        $model = new UsuarioModel();
        $usuario = $model->buscarPorCorreo($correo);

        if (!$usuario || !password_verify($password, $usuario['password']) || $usuario['estado'] !== 'activo') {
            $_SESSION['mensaje_login'] = 'Correo o contraseña erroneos';
            header('Location: ' . $paginaOrigen);
            exit;
        }

        session_regenerate_id(true);
        // GUARDA SESION
        $_SESSION['idUsuario'] = $usuario['idUsuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
        
        // REDIRECCION A APARTADO POR ROL
        if ($usuario['rol'] === 'admin') {
            // $_SESSION['mensaje_login'] = 'Login correcto. Redirigiendo al panel.';
            header('Location: /PJF/admin');
            exit;
        } elseif ($usuario['rol'] === 'empleado') {
            header('Location: /PJF/empleado');
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

        // ELIMINAR COOKIE
        if (ini_get('session.use_cookies')) {
            $parametros = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $parametros['path'],
                $parametros['domain'],
                $parametros['secure'],
                $parametros['httponly']
            );
        }

        header('Location: /PJF');
        exit;
    }


    // VERIFICACION DE MODELO registrarUsuario
    public function registrar()
    {   
        //VALIDA SI ES POST
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: /PJF/');
            exit;
        }
        // ================================================
        // RECUPERAR Y VALIDAR PAGINA ORIGINAL
        $paginaOrigen = $_POST['paginaOrigen'] ?? '/PJF';

        $paginasPermitidas = [
            '/PJF',
            '/PJF/',
            '/PJF/menu',
            '/PJF/reserva'
        ];
        // VALIDACION DE RUTAS
        if (!is_string($paginaOrigen) ||
            !in_array($paginaOrigen, $paginasPermitidas, true)) {
            $paginaOrigen = '/PJF';
        }
        // =================================================
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
            header('Location: ' . $paginaOrigen);
            exit;
        }
        // VALIDAR FORMATO DEL CORREO
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensaje_registro'] = 'Ingrese un correo electrónico válido.';
            header('Location: ' . $paginaOrigen);
            exit;
        }
        // LONGITUD DE LOS DATOS
        if (mb_strlen($nombre) > 100 || strlen($correo) > 150 || strlen($telefono) > 10) {
            $_SESSION['mensaje_registro'] = 'Uno de los campos supera la longitud permitida.';
            header('Location: ' . $paginaOrigen);
            exit;
        }
        // VALIDAR  QUE LAS CONTRASENAS CONCIDAN
        if ($password !== $confirmarPassword) {
            $_SESSION['mensaje_registro'] = 'Las contraseñas no coinciden.';
            header('Location: ' . $paginaOrigen);
            exit;
        }
        // VALIDAR LONGITUD DE CONTRASENA
        if (strlen($password) < 8) {
            $_SESSION['mensaje_registro'] = 'La contraseña debe tener al menos 8 caracteres.';
            header('Location: ' . $paginaOrigen);
            exit;
        }
        // VALIDAR TRAMIENTO DE  DATOS 
        if ($aceptaTratamiento !== '1') {
            $_SESSION['mensaje_registro'] = 'Debe aceptar el tratamiento de datos personales.';
            header('Location: ' . $paginaOrigen);
            exit;
        }
        // CONSULTAR DEL METODO BUCAR CORREO SI EXISTE EL INGRESADO
        $model = new  UsuarioModel();

        if ($model->buscarPorCorreo($correo)) {
            $_SESSION['mensaje_registro'] = 'Este correo ya se encuentra registrado.';
            header('Location: ' . $paginaOrigen);
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

                header('Location: ' . $paginaOrigen);
                exit;

            } catch (PDOException $e) {

                error_log($e->getMessage());
                // die('ERROR SQL: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
                $_SESSION['mensaje_registro'] = 'No fue posible completar el registro. Inténtalo nuevamente.';

                header('Location: ' . $paginaOrigen);
                exit;
            }
        }
    }
