<?php
require_once __DIR__ . '/app/models/UsuarioModel.php';

$model = new UsuarioModel();
var_dump($model->buscarPorCorreo('admin@admin.com'));

$usuario = false;

/*
if (!$usuario || $usuario['estado'] !== 'activo') {
    echo "bloqueado\n";
};
if ($usuario['estado'] !== 'activo' || !$usuario) {
    echo "bloqueado\n";
};
*/
