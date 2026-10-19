
<?php

class Database
{
// ==================================================================================================
    private $host = 'localhost';
    private $dbname = 'restaurante';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
// ==================================================================================================
/*
CONEXION A LA db
*/
    public function conectar()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $conexion = new PDO(
                $dsn,
                $this->username,
                $this->password,
                $opciones
            );

            return $conexion;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw new RuntimeException('No se pudo conectar con la base de datos.');
        }
    }
    
}
