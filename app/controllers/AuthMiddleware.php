<?php
class AuthMiddleware{
    // PROTEGE RUTAS EXCLUSIVAS PARA ADMINS
    public static function soloAdmin()
    {
        if (!isset($_SESSION['idUsuario'])) {
            header('Location: /PJF/login');
            exit;
        }

        if (($_SESSION['rol'] ?? '') !== 'admin') {
            http_response_code(403);
            echo 'Acceso denegado';
            exit;
        }
    }
}
