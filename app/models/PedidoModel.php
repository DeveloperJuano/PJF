<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/Estados.php';

class PedidoModel
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Database();
        $this->conexion = $db->conectar();
    }

// ==================================================================================================
// MESAS
// ==================================================================================================
/*
Mesas habilitadas, con un 1 en "ocupada" si tienen algun pedido activo
(por confirmar, en preparacion o por pagar).
La ocupacion se calcula con los pedidos, no se guarda en la mesa.
*/
    public function obtenerMesasConOcupacion()
    {
        $activos = implode(', ', [
            EstadoPedido::POR_CONFIRMAR,
            EstadoPedido::EN_PREPARACION,
            EstadoPedido::POR_PAGAR,
        ]);

        $sql = "SELECT m.idMesa, m.numero, m.capacidad,
                       EXISTS (
                           SELECT 1
                           FROM pedido p
                           WHERE p.idMesa = m.idMesa
                             AND p.estado IN ($activos)
                       ) AS ocupada
                FROM mesa m
                WHERE m.estado <> 'inactiva'
                ORDER BY m.numero ASC";

        return $this->conexion->query($sql)->fetchAll();
    }

// ==================================================================================================
// PANEL DEL EMPLEADO
// ==================================================================================================

    public function obtenerPanel()
    {
        return [
            'porConfirmar' => $this->pedidosConLineas(EstadoPedido::POR_CONFIRMAR),
            'cola' => $this->obtenerCola(),
            'porPagar' => $this->pedidosConLineas(EstadoPedido::POR_PAGAR),
        ];
    }

// ==================================================================================================
/*
Pedidos en un estado dado, cada uno con sus productos en la clave "lineas".
*/
    private function pedidosConLineas($estado)
    {
        $sql = "SELECT p.idPedido, p.nombre, p.total, p.fechaHoraInicio, m.numero AS mesa
                FROM pedido p
                INNER JOIN mesa m ON m.idMesa = p.idMesa
                WHERE p.estado = :estado
                ORDER BY p.fechaHoraInicio ASC, p.idPedido ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':estado' => $estado]);
        $pedidos = $consulta->fetchAll();

        if (!$pedidos) {
            return [];
        }

        $ids = array_column($pedidos, 'idPedido');
        $marcas = implode(', ', array_fill(0, count($ids), '?'));

        $sqlLineas = "SELECT d.idPedido, d.cantidadProducto, d.subtotal, pr.nombre AS producto
                      FROM detallePedido d
                      INNER JOIN producto pr ON pr.idProducto = d.idProducto
                      WHERE d.idPedido IN ($marcas)
                      ORDER BY d.idDetalle ASC";

        $consultaLineas = $this->conexion->prepare($sqlLineas);
        $consultaLineas->execute($ids);

        $porPedido = [];
        foreach ($consultaLineas->fetchAll() as $linea) {
            $porPedido[$linea['idPedido']][] = $linea;
        }

        foreach ($pedidos as $indice => $pedido) {
            $pedidos[$indice]['lineas'] = $porPedido[$pedido['idPedido']] ?? [];
        }

        return $pedidos;
    }

// ==================================================================================================
/*
LA COLA
Productos en cola o sirviendo de pedidos que estan en preparacion,
del pedido confirmado hace mas tiempo al mas reciente.
*/
    public function obtenerCola()
    {
        $sql = "SELECT d.idDetalle, d.idPedido, d.cantidadProducto, d.estado,
                       pr.nombre AS producto,
                       m.numero AS mesa,
                       p.nombre AS cliente,
                       p.fechaHoraConfirmado,
                       (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(p.fechaHoraConfirmado)) AS segundosEspera
                FROM detallePedido d
                INNER JOIN pedido p ON p.idPedido = d.idPedido
                INNER JOIN producto pr ON pr.idProducto = d.idProducto
                INNER JOIN mesa m ON m.idMesa = p.idMesa
                WHERE p.estado = :estadoPedido
                  AND d.estado IN (:enCola, :sirviendo)
                ORDER BY p.fechaHoraConfirmado ASC, d.idDetalle ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            ':estadoPedido' => EstadoPedido::EN_PREPARACION,
            ':enCola' => EstadoProducto::EN_COLA,
            ':sirviendo' => EstadoProducto::SIRVIENDO,
        ]);

        return $consulta->fetchAll();
    }

// ==================================================================================================
// CREAR PEDIDO
// ==================================================================================================
/*
$cantidades => [idProducto => cantidad]
Crea el pedido y sus productos en una transaccion: o se guarda todo o nada.
El precio se toma de la base de datos (nunca del formulario) y se copia
en precioUnitario para conservar el precio historico.
Devuelve el id del pedido nuevo. Lanza DomainException si algo no es valido.
*/
    public function crearPedido($nombre, $idMesa, array $cantidades)
    {
        if (count($cantidades) === 0) {
            throw new DomainException('Agrega al menos un producto.');
        }

        $this->conexion->beginTransaction();

        try {
            // mesa existente y habilitada
            $consultaMesa = $this->conexion->prepare(
                "SELECT idMesa FROM mesa WHERE idMesa = :idMesa AND estado <> 'inactiva'"
            );
            $consultaMesa->execute([':idMesa' => $idMesa]);

            if (!$consultaMesa->fetch()) {
                throw new DomainException('La mesa no existe o está inactiva.');
            }

            // precios reales de los productos activos
            $ids = array_keys($cantidades);
            $marcas = implode(', ', array_fill(0, count($ids), '?'));

            $consultaProductos = $this->conexion->prepare(
                "SELECT idProducto, precio
                 FROM producto
                 WHERE estado = 'activo' AND idProducto IN ($marcas)"
            );
            $consultaProductos->execute($ids);

            $precios = [];
            foreach ($consultaProductos->fetchAll() as $fila) {
                $precios[(int) $fila['idProducto']] = (float) $fila['precio'];
            }

            if (count($precios) !== count($ids)) {
                throw new DomainException('Hay productos que ya no están disponibles.');
            }

            // encabezado del pedido
            $insertarPedido = $this->conexion->prepare(
                "INSERT INTO pedido (nombre, idMesa, estado, total)
                 VALUES (:nombre, :idMesa, :estado, 0)"
            );
            $insertarPedido->execute([
                ':nombre' => $nombre,
                ':idMesa' => $idMesa,
                ':estado' => EstadoPedido::POR_CONFIRMAR,
            ]);

            $idPedido = (int) $this->conexion->lastInsertId();

            // lista de productos
            $insertarLinea = $this->conexion->prepare(
                "INSERT INTO detallePedido
                    (idPedido, idProducto, cantidadProducto, precioUnitario, subtotal, estado)
                 VALUES
                    (:idPedido, :idProducto, :cantidad, :precio, :subtotal, :estado)"
            );

            $total = 0;

            foreach ($cantidades as $idProducto => $cantidad) {
                $precio = $precios[(int) $idProducto];
                $subtotal = $precio * $cantidad;
                $total += $subtotal;

                $insertarLinea->execute([
                    ':idPedido' => $idPedido,
                    ':idProducto' => $idProducto,
                    ':cantidad' => $cantidad,
                    ':precio' => $precio,
                    ':subtotal' => $subtotal,
                    ':estado' => EstadoProducto::POR_CONFIRMAR,
                ]);
            }

            $actualizarTotal = $this->conexion->prepare(
                "UPDATE pedido SET total = :total WHERE idPedido = :idPedido"
            );
            $actualizarTotal->execute([
                ':total' => $total,
                ':idPedido' => $idPedido,
            ]);

            $this->conexion->commit();

            return $idPedido;
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }

// ==================================================================================================
// CONFIRMAR PEDIDO
// ==================================================================================================
/*
Pedido: por confirmar -> en preparacion (guarda quien y cuando).
Productos: por confirmar -> en cola. Entran a la cola en este momento.
Devuelve false si el pedido ya no estaba por confirmar.
*/
    public function confirmarPedido($idPedido, $idEmpleado)
    {
        $this->conexion->beginTransaction();

        try {
            $pedido = $this->conexion->prepare(
                "UPDATE pedido
                 SET estado = :nuevo,
                     fechaHoraConfirmado = NOW(),
                     idEmpleado = :idEmpleado
                 WHERE idPedido = :idPedido
                   AND estado = :actual"
            );
            $pedido->execute([
                ':nuevo' => EstadoPedido::EN_PREPARACION,
                ':idEmpleado' => $idEmpleado,
                ':idPedido' => $idPedido,
                ':actual' => EstadoPedido::POR_CONFIRMAR,
            ]);

            if ($pedido->rowCount() === 0) {
                $this->conexion->rollBack();
                return false;
            }

            $lineas = $this->conexion->prepare(
                "UPDATE detallePedido
                 SET estado = :nuevo
                 WHERE idPedido = :idPedido
                   AND estado = :actual"
            );
            $lineas->execute([
                ':nuevo' => EstadoProducto::EN_COLA,
                ':idPedido' => $idPedido,
                ':actual' => EstadoProducto::POR_CONFIRMAR,
            ]);

            $this->conexion->commit();

            return true;
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }

// ==================================================================================================
// AVANZAR UN PRODUCTO
// ==================================================================================================
/*
Producto: en cola -> sirviendo -> servido.
Cuando ya no queda ningun producto por servir, el pedido pasa a "por pagar".
*/
    public function avanzarProducto($idDetalle)
    {
        $this->conexion->beginTransaction();

        try {
            $buscar = $this->conexion->prepare(
                "SELECT d.idPedido, d.estado
                 FROM detallePedido d
                 INNER JOIN pedido p ON p.idPedido = d.idPedido
                 WHERE d.idDetalle = :idDetalle
                   AND p.estado = :estadoPedido"
            );
            $buscar->execute([
                ':idDetalle' => $idDetalle,
                ':estadoPedido' => EstadoPedido::EN_PREPARACION,
            ]);
            $linea = $buscar->fetch();

            $estadoActual = $linea ? (int) $linea['estado'] : 0;

            if ($estadoActual !== EstadoProducto::EN_COLA && $estadoActual !== EstadoProducto::SIRVIENDO) {
                $this->conexion->rollBack();
                return false;
            }

            $actualizar = $this->conexion->prepare(
                "UPDATE detallePedido
                 SET estado = :nuevo
                 WHERE idDetalle = :idDetalle
                   AND estado = :actual"
            );
            $actualizar->execute([
                ':nuevo' => $estadoActual + 1,
                ':idDetalle' => $idDetalle,
                ':actual' => $estadoActual,
            ]);

            if ($actualizar->rowCount() === 0) {
                $this->conexion->rollBack();
                return false;
            }

            // ¿queda algo por servir en este pedido?
            $pendientes = $this->conexion->prepare(
                "SELECT COUNT(*) AS total
                 FROM detallePedido
                 WHERE idPedido = :idPedido
                   AND estado IN (:porConfirmar, :enCola, :sirviendo)"
            );
            $pendientes->execute([
                ':idPedido' => $linea['idPedido'],
                ':porConfirmar' => EstadoProducto::POR_CONFIRMAR,
                ':enCola' => EstadoProducto::EN_COLA,
                ':sirviendo' => EstadoProducto::SIRVIENDO,
            ]);

            if ((int) $pendientes->fetch()['total'] === 0) {
                $cerrar = $this->conexion->prepare(
                    "UPDATE pedido
                     SET estado = :nuevo
                     WHERE idPedido = :idPedido
                       AND estado = :actual"
                );
                $cerrar->execute([
                    ':nuevo' => EstadoPedido::POR_PAGAR,
                    ':idPedido' => $linea['idPedido'],
                    ':actual' => EstadoPedido::EN_PREPARACION,
                ]);
            }

            $this->conexion->commit();

            return true;
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }

// ==================================================================================================
// CANCELAR PEDIDO
// ==================================================================================================
/*
Se puede cancelar mientras este por confirmar o en preparacion.
Los productos que no se han servido quedan como rechazados (-1).
*/
    public function cancelarPedido($idPedido)
    {
        $this->conexion->beginTransaction();

        try {
            $pedido = $this->conexion->prepare(
                "UPDATE pedido
                 SET estado = :cancelado
                 WHERE idPedido = :idPedido
                   AND estado IN (:porConfirmar, :enPreparacion)"
            );
            $pedido->execute([
                ':cancelado' => EstadoPedido::CANCELADO,
                ':idPedido' => $idPedido,
                ':porConfirmar' => EstadoPedido::POR_CONFIRMAR,
                ':enPreparacion' => EstadoPedido::EN_PREPARACION,
            ]);

            if ($pedido->rowCount() === 0) {
                $this->conexion->rollBack();
                return false;
            }

            $lineas = $this->conexion->prepare(
                "UPDATE detallePedido
                 SET estado = :rechazado
                 WHERE idPedido = :idPedido
                   AND estado <> :servido"
            );
            $lineas->execute([
                ':rechazado' => EstadoProducto::RECHAZADO,
                ':idPedido' => $idPedido,
                ':servido' => EstadoProducto::SERVIDO,
            ]);

            $this->conexion->commit();

            return true;
        } catch (Throwable $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            throw $e;
        }
    }

// ==================================================================================================
// CAJA BETA: REGISTRAR EL PAGO
// ==================================================================================================
/*
Solo se puede pagar un pedido que esta por pagar.
Crea la fila de pago y deja el pedido como completado.
*/
    public function pagarPedido($idPedido, $metodoPago)
    {
        $this->conexion->beginTransaction();

        try {
            $buscar = $this->conexion->prepare(
                "SELECT total
                 FROM pedido
                 WHERE idPedido = :idPedido
                   AND estado = :estado"
            );
            $buscar->execute([
                ':idPedido' => $idPedido,
                ':estado' => EstadoPedido::POR_PAGAR,
            ]);
            $pedido = $buscar->fetch();

            if (!$pedido) {
                $this->conexion->rollBack();
                return false;
            }

            $pago = $this->conexion->prepare(
                "INSERT INTO pago (idPedido, fecha, metodoPago, totalPago, estado, hora)
                 VALUES (:idPedido, CURDATE(), :metodo, :total, 'pagado', CURTIME())"
            );
            $pago->execute([
                ':idPedido' => $idPedido,
                ':metodo' => $metodoPago,
                ':total' => $pedido['total'],
            ]);

            $cerrar = $this->conexion->prepare(
                "UPDATE pedido
                 SET estado = :nuevo
                 WHERE idPedido = :idPedido
                   AND estado = :actual"
            );
            $cerrar->execute([
                ':nuevo' => EstadoPedido::COMPLETADO,
                ':idPedido' => $idPedido,
                ':actual' => EstadoPedido::POR_PAGAR,
            ]);

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
