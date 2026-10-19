
<?php

require_once __DIR__ . '/../../config/db.php';

class MesaModel
{
// ==================================================================================================

    private PDO $conexion;

    public function __construct()
    {
        $db = new Database();
        $this->conexion = $db->conectar();
    }
// ==================================================================================================
/*
CONSULTA PARA OPTENER MESA
RETURN => idMesa, numero, capacidad, estado
DE MESA
*/
    public function obtenerMesas()
    {
        $sql = "SELECT idMesa, numero, capacidad, estado
                FROM mesa
                ORDER BY numero ASC";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll();
    }
// ==================================================================================================
/*
CONSULTA PARA CREAR MESA
=> $numero, $capacidad
INSERT => numero, capacidad, estado
*/
    public function crearMesa($numero, $capacidad)
    {
        $sql = "INSERT INTO mesa (numero, capacidad, estado)
                VALUES (:numero, :capacidad, 'disponible')";

        try {
            $consulta = $this->conexion->prepare($sql);

            return $consulta->execute([
                ':numero' => $numero,
                ':capacidad' => $capacidad
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return false;
            }

            throw $e;
        }
    }
// ==================================================================================================
/*
CONSULTA PARA ACTUALIZAR MESA
=> $numero, $capacidad
UPDATE=>  capacidad
*/
    public function actualizarCapacidad($numero, $capacidad)
    {
        $sql = "UPDATE mesa
                SET capacidad = :capacidad
                WHERE numero = :numero";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            ':capacidad' => $capacidad,
            ':numero' => $numero
        ]);

        return $consulta->rowCount() > 0;
    }
// ==================================================================================================
/*
CONSULTA PARA CAMBIAR ESTADO DE MESA
=> $numero, $estado
UPDATE=>  estado
*/

    private function cambiarEstado($numero, $estado)
    {
        $sql = "UPDATE mesa
                SET estado = :estado
                WHERE numero = :numero";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            ':estado' => $estado,
            ':numero' => $numero
        ]);

        return $consulta->rowCount() > 0;
    }

// ==================================================================================================
/*
*ES DEPENDIENTE DE FUNCION CAMBIAR ESTADO*

FUNCION PARA DESACTIVAR MESA
=> $numero
UPDATE =>, estado
*/

    public function desactivarMesa($numero)
    {
        return $this->cambiarEstado($numero, 'inactiva');
    }

// ==================================================================================================
/*
*ES DEPENDIENTE DE FUNCION CAMBIAR ESTADO*

FUNCION PARA ACTIVAR MESA
=> $numero
UPDATE =>, estado
*/

    public function activarMesa($numero)
    {
        return $this->cambiarEstado($numero, 'disponible');
    }
// ==================================================================================================

}
