<?php
class AuthMiddleware{
    public static function soloAdmin()
    {
        if (
            !isset($_SESSION['idUsuario']) ||
            !isset($_SESSION['rol']) ||
            $_SESSION['rol'] !== 'admin'
        ) {
            header('Location: /PJF/login');
            exit;
        }
    }
}