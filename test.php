<?php
require_once __DIR__ . '/app/models/UsuarioModel.php';

$model = new UsuarioModel();
var_dump($model->buscarPorCorreo('admin@admin.com'));
