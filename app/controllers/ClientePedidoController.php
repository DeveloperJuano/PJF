<?php

require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/ReservaModel.php';
require_once __DIR__ . '/../helpers/Csrf.php';
require_once __DIR__ . '/../helpers/vista.php';

/*
PEDIDO DEL CLIENTE
Todas las rutas /pedido/... pasan por AuthMiddleware::soloCliente() en web.php.
1. /pedido/resumen (POST)  el menu envia el carrito (solo id y cantidad)
2. /pedido/pago    (GET)   resumen y pago SIMULADO
3. /pedido/pagar   (POST)  crea el pedido (por confirmar) y su pago simulado
Desde ahi solo el empleado cambia el estado del pedido.
*/
class ClientePedidoController
{
    const MAX_CANTIDAD = 50;
    const METODOS_PAGO = ['tarjeta', 'pse', 'nequi'];

    // 1. GUARDAR EL CARRITO
    public function resumen()
    {
        $this->exigirPost('/PJF/menu');

        $carrito = [];
        $enviadas = $_POST['cantidad'] ?? [];

        if (is_array($enviadas)) {
            foreach ($enviadas as $idProducto => $cantidad) {
                $idProducto = filter_var($idProducto, FILTER_VALIDATE_INT);
                $cantidad = filter_var($cantidad, FILTER_VALIDATE_INT);

                if ($idProducto === false || $cantidad === false || $cantidad < 1) {
                    continue;
                }

                $carrito[$idProducto] = min($cantidad, self::MAX_CANTIDAD);
            }
        }

        if (count($carrito) === 0) {
            $this->volver('Agrega al menos un producto.', '/PJF/menu');
        }

        $_SESSION['carrito'] = $carrito;

        header('Location: /PJF/pedido/pago');
        exit;
    }

    // 2. PANTALLA DE PAGO (SIMULADA)
    public function pago()
    {
        $carrito = $_SESSION['carrito'] ?? [];

        if (!$carrito) {
            $this->volver('Tu pedido está vacío.', '/PJF/menu');
        }

        try {
            $modelo = new PedidoModel();
            $resumen = $modelo->resumenCarrito($carrito);
        } catch (DomainException $e) {
            unset($_SESSION['carrito']);
            $this->volver($e->getMessage(), '/PJF/menu');
        }

        $reservas = (new ReservaModel())->obtenerReservasActivasDe((int) $_SESSION['idUsuario']);
        $metodos = self::METODOS_PAGO;

        require __DIR__ . '/../views/cliente/pago.php';
    }

    // 3. PAGAR (SIMULADO) Y CREAR EL PEDIDO
    public function pagar()
    {
        $this->exigirPost('/PJF/pedido/pago');

        $carrito = $_SESSION['carrito'] ?? [];

        if (!$carrito) {
            $this->volver('Tu pedido está vacío.', '/PJF/menu');
        }

        $idReserva = filter_input(INPUT_POST, 'idReserva', FILTER_VALIDATE_INT);
        $metodo = $_POST['metodo'] ?? '';

        if (!in_array($metodo, self::METODOS_PAGO, true)) {
            $this->volver('Elige un método de pago.', '/PJF/pedido/pago');
        }

        // la reserva debe ser DEL CLIENTE de la sesion y estar activa ahora.
        // la mesa sale de la base de datos, nunca del formulario.
        $idUsuario = (int) $_SESSION['idUsuario'];
        $reservaElegida = null;

        foreach ((new ReservaModel())->obtenerReservasActivasDe($idUsuario) as $reserva) {
            if ((int) $reserva['idReserva'] === $idReserva) {
                $reservaElegida = $reserva;
            }
        }

        if ($reservaElegida === null) {
            $this->volver('Para pedir necesitas una reserva activa. Elige una válida.', '/PJF/pedido/pago');
        }

        try {
            $modelo = new PedidoModel();
            $idPedido = $modelo->crearPedido(
                $_SESSION['nombre'],
                (int) $reservaElegida['idMesa'],
                $carrito,
                [
                    'idUsuario' => $idUsuario,
                    'idReserva' => (int) $reservaElegida['idReserva'],
                    'metodoPago' => $metodo . ' (simulado)',
                ]
            );
        } catch (DomainException $e) {
            $this->volver($e->getMessage(), '/PJF/pedido/pago');
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $this->volver('No se pudo registrar el pedido. Intenta de nuevo.', '/PJF/pedido/pago');
        }

        unset($_SESSION['carrito']);

        $this->volver("Pago simulado aprobado. Tu pedido #$idPedido fue enviado y un empleado lo confirmará.", '/PJF/menu');
    }

    // AYUDAS PRIVADAS
    private function exigirPost($destino)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $destino);
            exit;
        }

        if (!Csrf::validar()) {
            $this->volver('La sesión del formulario expiró. Intenta de nuevo.', $destino);
        }
    }

    private function volver($mensaje, $destino)
    {
        $_SESSION['mensaje_pedido'] = $mensaje;
        header('Location: ' . $destino);
        exit;
    }
}