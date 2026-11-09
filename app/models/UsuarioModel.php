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

    //FUNCION DE RESGISTRO
    // REGISTRAR USUARIO Y CONSENTIMIENTO
    public function registrarUsuario($nombre, $correo, $telefono, $password, $versionPolitica)
    {
        try {
            $this->conexion->beginTransaction();

            //REGISTRAR USUARIO
            $sql = "INSERT INTO usuario
                    (nombre, correo, telefono, password, rol, estado)
                    VALUES
                    (:nombre, :correo, :telefono, :password, 'cliente', 'activo')";

            $consulta = $this->conexion->prepare($sql);

            $consulta->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':telefono' => $telefono,
                ':password' => password_hash($password, PASSWORD_DEFAULT)
            ]);

            //OBTENER ID DEL USUARIO CREADO
            $idUsuario = $this->conexion->lastInsertId();

            //REGISTRAR CONSENTIMIENTO
            $sql = "INSERT INTO consentimientoDatos
                    (idUsuario, versionPolitica, aceptado)
                    VALUES
                    (:idUsuario, :versionPolitica, 1)";

            $consulta = $this->conexion->prepare($sql);

            $consulta->execute([
                ':idUsuario' => $idUsuario,
                ':versionPolitica' => $versionPolitica
            ]);

            // 4. CONFIRMAR TRANSACCIÓN
            $this->conexion->commit();

            return true;

        } catch (PDOException $e) {

            // SI ALGO FALLA, REVERTIR LOS CAMBIOS
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }
    // REGISTRO EMPLEADO Y CONSENTIMIENTO 
    // REGISTRAR EMPLEADO Y CONSENTIMIENTO
    public function registrarEmpleado($nombre, $correo, $telefono, $password, $versionPolitica)
    {
        try {
            $this->conexion->beginTransaction();

            // 1. REGISTRAR EMPLEADO
            $sql = "INSERT INTO usuario
                    (nombre, correo, telefono, password, rol, estado)
                    VALUES
                    (:nombre, :correo, :telefono, :password, 'empleado', 'activo')";

            $consulta = $this->conexion->prepare($sql);

            $consulta->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':telefono' => $telefono,
                ':password' => password_hash($password, PASSWORD_DEFAULT)
            ]);

            // 2. OBTENER ID DEL EMPLEADO
            $idUsuario = $this->conexion->lastInsertId();

            // 3. REGISTRAR CONFIRMACIÓN DEL CONSENTIMIENTO
            $sql = "INSERT INTO consentimientoDatos
                    (idUsuario, versionPolitica, aceptado)
                    VALUES
                    (:idUsuario, :versionPolitica, 1)";

            $consulta = $this->conexion->prepare($sql);

            $consulta->execute([
                ':idUsuario' => $idUsuario,
                ':versionPolitica' => $versionPolitica
            ]);

            // 4. CONFIRMAR TRANSACCIÓN
            $this->conexion->commit();

            return true;

        } catch (PDOException $e) {

            // REVERTIR SI OCURRE UN ERROR
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }
}
