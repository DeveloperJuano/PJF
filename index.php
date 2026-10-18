<?php
/*
guarda la pagina offline/sin logueo \\
test
*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/routes/web.php';

?>