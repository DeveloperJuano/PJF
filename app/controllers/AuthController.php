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
            header('Location: /PJF/admin/mesas');
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
}
