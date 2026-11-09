<?php

require_once __DIR__ . '/../../config/db.php';

class ReservaModel
{
    // ==========================================================
    // CONEXIÓN
    // ==========================================================

    private PDO $conexion;

    public function __construct()
    {
        $db = new Database();
        $this->conexion = $db->conectar();
    }

    // ==========================================================
    /**
     * CONSULTA PARA OBTENER TODAS LAS RESERVAS
     *
     * RETURN:
     * idReserva, cliente, correo, telefono,
     * numeroMesa, fecha, hora, personas,
     * descripcionReserva, estado
     */
    // ==========================================================
    // TODAS LAS  RESERVAS
    public function obtenerReservas()
    {
        $sql = "SELECT
                    r.idReserva,
                    u.nombre AS cliente,
                    u.correo,
                    u.telefono,
                    m.numero AS numeroMesa,
                    r.fecha,
                    r.hora,
                    r.personas,
                    r.descripcionReserva,
                    r.estado
                FROM reserva AS r
                INNER JOIN usuario AS u
                    ON r.idUsuario = u.idUsuario
                INNER JOIN mesa AS m
                    ON r.idMesa = m.idMesa
                ORDER BY r.fecha DESC, r.hora DESC";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    // RESERVAS PROXIMAS
    public function obtenerReservasProximas()
    {
        $sql = "SELECT
                    r.idReserva,
                    u.nombre AS cliente,
                    u.correo,
                    u.telefono,
                    m.numero AS numeroMesa,
                    r.fecha,
                    r.hora,
                    r.personas,
                    r.descripcionReserva,
                    r.estado
                FROM reserva AS r
                INNER JOIN usuario AS u
                    ON r.idUsuario = u.idUsuario
                INNER JOIN mesa AS m
                    ON r.idMesa = m.idMesa
                WHERE r.fecha >= CURDATE()
                AND r.estado IN ('pendiente', 'confirmada')
                ORDER BY r.fecha ASC, r.hora ASC";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    // PASADAS
    public function obtenerReservasPasadas()
    {
        $sql = "SELECT
                    r.idReserva,
                    u.nombre AS cliente,
                    u.correo,
                    u.telefono,
                    m.numero AS numeroMesa,
                    r.fecha,
                    r.hora,
                    r.personas,
                    r.descripcionReserva,
                    r.estado
                FROM reserva AS r
                INNER JOIN usuario AS u
                    ON r.idUsuario = u.idUsuario
                INNER JOIN mesa AS m
                    ON r.idMesa = m.idMesa
                WHERE r.fecha < CURDATE()
                ORDER BY r.fecha DESC, r.hora DESC";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    // CONFIRMAR RESERVAS
    public function confirmarReserva(int $idReserva): bool
    {
        // ACTUALIZAR ESTADO DE LA RESERVA
        $sql = "UPDATE reserva
                SET estado = 'confirmada'
                WHERE idReserva = :idReserva
                AND estado = 'pendiente'";

        // PREPARAR CONSULTA
        $consulta = $this->conexion->prepare($sql);

        // EJECUTAR CONSULTA
        $consulta->execute([
            ':idReserva' => $idReserva
        ]);

        // VERIFICAR SI SE ACTUALIZO LA RESERVA
        return $consulta->rowCount() > 0;
    }
    // CANCELAR
    public function cancelarReserva(int $idReserva): bool
    {
        // ACTUALIZAR ESTADO DE LA RESERVA
        $sql = "UPDATE reserva
                SET estado = 'cancelada'
                WHERE idReserva = :idReserva
                AND estado IN ('pendiente', 'confirmada')";

        // PREPARAR CONSULTA
        $consulta = $this->conexion->prepare($sql);

        // EJECUTAR CONSULTA
        $consulta->execute([
            ':idReserva' => $idReserva
        ]);

        // VERIFICAR SI SE ACTUALIZO LA RESERVA
        return $consulta->rowCount() > 0;
    }
    // ==========================================================
    // REGISTRAR RESERVA
    // ==========================================================

    public function registrarReserva(
        int $idUsuario,
        int $idMesa,
        string $fecha,
        string $hora,
        int $personas
    ): bool {

        try {

            // INICIAR TRANSACCIÓN
            // BLOQUEA MESA
            $this->conexion->beginTransaction();

            // BLOQUEAR LA MESA
            $sql = "SELECT idMesa, capacidad, estado
                    FROM mesa
                    WHERE idMesa = :idMesa
                    FOR UPDATE";

            $consulta = $this->conexion->prepare($sql);

            $consulta->execute([
                ':idMesa' => $idMesa
            ]);

            $mesa = $consulta->fetch(PDO::FETCH_ASSOC);

            // VALIDAR MESA Y CAPACIDAD
            if (
                !$mesa ||
                $mesa['estado'] !== 'disponible' ||
                $personas < 1 ||
                $personas > (int) $mesa['capacidad']
            ) {
                $this->conexion->rollBack();
                return false;
            }

            // VALIDAR CONFLICTOS DE HORARIOS
            $sql = "SELECT idReserva
                    FROM reserva
                    WHERE idMesa = :idMesa
                    AND fecha = :fecha
                    AND estado IN ('pendiente', 'confirmada')
                    -- LAPSO DE TIMEPO DE RESERVA PARA LA SIGUIENTE 
                    AND hora < ADDTIME(:horaInicio, '02:00:00')
                    AND ADDTIME(hora, '02:00:00') > :horaFin
                    LIMIT 1";

            $consulta = $this->conexion->prepare($sql);

            $consulta->execute([
                ':idMesa' => $idMesa,
                ':fecha' => $fecha,
                ':horaInicio' => $hora,
                ':horaFin' => $hora
            ]);

            if ($consulta->fetch()) {
                $this->conexion->rollBack();
                return false;
            }

            // INSERTAR RESERVA
            $sql = "INSERT INTO reserva
                        (idUsuario, idMesa, fecha, hora, personas, estado)
                    VALUES
                        (:idUsuario, :idMesa, :fecha, :hora, :personas, 'pendiente')";

            $consulta = $this->conexion->prepare($sql);

            $consulta->execute([
                ':idUsuario' => $idUsuario,
                ':idMesa' => $idMesa,
                ':fecha' => $fecha,
                ':hora' => $hora,
                ':personas' => $personas
            ]);

            // CONFIRMAR TRANSACCIÓN
            $this->conexion->commit();

            return true;

        } catch (Throwable $e) {

            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }
}