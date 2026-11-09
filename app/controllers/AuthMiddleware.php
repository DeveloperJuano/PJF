<?php
class AuthMiddleware{
    // PROTEGE RUTAS EXCLUSIVAS PARA ADMINS
    public static function soloAdmin()
    {
        if (!isset($_SESSION['idUsuario'])) {
            header('Location: /PJF');
            exit;
        }

        if (($_SESSION['rol'] ?? '') !== 'admin') {
            http_response_code(403);
            echo 'Acceso denegado';
            header('Location: /PJF');
            exit;
        }
    }

    // PROTEGE RUTAS PARA LOS EMPLEADOS
    public static function soloEmpleado()
    {
        if (!isset($_SESSION['idUsuario'])) {
            header('Location: /PJF');
            exit;
        }

        $rol = $_SESSION['rol'] ?? '';

        if ($rol !== 'empleado' && $rol !== 'admin') {
            http_response_code(403);
            echo 'Acceso denegado';
            header('Location: /PJF');
            exit;
        }
    }
}
