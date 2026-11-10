<?php


session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => !empty($_SERVER['HTTPS']),
]);

ini_set('session.use_strict_mode', '1');

date_default_timezone_set('America/Bogota');

session_start();

require_once __DIR__ . '/routes/web.php';
