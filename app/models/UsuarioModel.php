<?php

require_once __DIR__ . '/../../config/db.php';

class UsuarioModel
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Database();
        $this->conexion = $db->conectar();
    }

    public function buscarPorCorreo($correo)
    {
        $sql = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':correo' => $correo
        ]);

        return $consulta->fetch();
    }
}
