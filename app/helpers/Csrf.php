<?php

/*
PROTECCION CSRF
Cada formulario POST lleva un token secreto guardado en la sesion.
Si el token no llega o no coincide, el servidor rechaza la peticion.
Asi otra pagina no puede enviar un formulario a nombre del empleado.
*/
class Csrf
{
    public static function token()
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf'];
    }

    public static function campo()
    {
        return '<input type="hidden" name="csrf" value="'
            . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8')
            . '">';
    }

    public static function validar()
    {
        $enviado = $_POST['csrf'] ?? '';

        return is_string($enviado)
            && isset($_SESSION['csrf'])
            && hash_equals($_SESSION['csrf'], $enviado);
    }
}
