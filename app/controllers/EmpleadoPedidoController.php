<?php

require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/MenuModel.php';
require_once __DIR__ . '/../models/Estados.php';
require_once __DIR__ . '/../helpers/Csrf.php';
require_once __DIR__ . '/../helpers/vista.php';

/*
PANEL DEL EMPLEADO (beta)
Todas las rutas /empleado/... ya pasan por AuthMiddleware::soloEmpleado()
en routes/web.php, asi que aqui solo entran empleados y administradores.
*/
class EmpleadoPedidoController
{
    const MAX_CANTIDAD = 50;
    const METODOS_PAGO = ['efectivo', 'tarjeta', 'transferencia'];

// ==================================================================================================
// PAGINAS
// ==================================================================================================

    // pagina principal: pedidos por confirmar, cola y por pagar
    public function panel()
    {
        $model = new PedidoModel();
        $datos = $model->obtenerPanel();

        require __DIR__ . '/../views/empleado/panel.php';
    }

    // solo las secciones, para que el JavaScript las refresque cada pocos segundos
    public function fragmento()
    {
        header('Cache-Control: no-store');

        $model = new PedidoModel();
        $datos = $model->obtenerPanel();

        require __DIR__ . '/../views/empleado/_secciones.php';
    }

    // formulario para tomar un pedido
    public function nuevo()
    {
        $pedidoModel = new PedidoModel();
        $menuModel = new MenuModel();

        $mesas = $pedidoModel->obtenerMesasConOcupacion();

        // productos agrupados por categoria
        $productosPorCategoria = [];
        foreach ($menuModel->obtenerProducto() as $producto) {
            $productosPorCategoria[$producto['categoria']][] = $producto;
        }

        require __DIR__ . '/../views/empleado/nuevo.php';
    }

// ==================================================================================================
// ACCIONES (todas son POST con token CSRF)
// ==================================================================================================

    public function crear()
    {
        $this->exigirPost('/PJF/empleado/pedidos/nuevo');

        $nombre = trim($_POST['nombre'] ?? '');
        $idMesa = filter_input(INPUT_POST, 'idMesa', FILTER_VALIDATE_INT);

        if ($nombre === '' || mb_strlen($nombre) > 60) {
            $this->volver('Escribe el nombre del cliente (máximo 60 caracteres).', '/PJF/empleado/pedidos/nuevo');
        }

        if ($idMesa === false || $idMesa === null || $idMesa < 1) {
            $this->volver('Elige una mesa.', '/PJF/empleado/pedidos/nuevo');
        }

        // cantidad[idProducto] => cantidad ; se ignoran las cantidades en 0
        $cantidades = [];
        $enviadas = $_POST['cantidad'] ?? [];

        if (is_array($enviadas)) {
            foreach ($enviadas as $idProducto => $cantidad) {
                $idProducto = filter_var($idProducto, FILTER_VALIDATE_INT);
                $cantidad = filter_var($cantidad, FILTER_VALIDATE_INT);

                if ($idProducto === false || $cantidad === false || $cantidad < 1) {
                    continue;
                }

                $cantidades[$idProducto] = min($cantidad, self::MAX_CANTIDAD);
            }
        }

        if (count($cantidades) === 0) {
            $this->volver('Agrega al menos un producto.', '/PJF/empleado/pedidos/nuevo');
        }

        try {
            $model = new PedidoModel();
            $idPedido = $model->crearPedido($nombre, $idMesa, $cantidades);
        } catch (DomainException $e) {
            $this->volver($e->getMessage(), '/PJF/empleado/pedidos/nuevo');
        } catch (Throwable $e) {
            error_log($e->getMessage());
            $this->volver('No se pudo crear el pedido. Intenta de nuevo.', '/PJF/empleado/pedidos/nuevo');
        }

        $this->volver("Pedido #$idPedido creado. Confírmalo para enviarlo a la cola.");
    }

// ==================================================================================================
    public function confirmar()
    {
        $this->exigirPost();

        $idPedido = $this->leerId('idPedido');

        // el empleado sale de la SESION, nunca del formulario
        $idEmpleado = (int) $_SESSION['idUsuario'];

        $model = new PedidoModel();

        if ($idPedido !== null && $model->confirmarPedido($idPedido, $idEmpleado)) {
            $this->volver("Pedido #$idPedido confirmado y en cola.");
        }

        $this->volver('No se pudo confirmar: el pedido ya no está por confirmar.');
    }

// ==================================================================================================
    public function cancelar()
    {
        $this->exigirPost();

        $idPedido = $this->leerId('idPedido');
        $model = new PedidoModel();

        if ($idPedido !== null && $model->cancelarPedido($idPedido)) {
            $this->volver("Pedido #$idPedido cancelado.");
        }

        $this->volver('No se pudo cancelar el pedido.');
    }

// ==================================================================================================
    public function avanzarProducto()
    {
        $this->exigirPost();

        $idDetalle = $this->leerId('idDetalle');
        $model = new PedidoModel();

        if ($idDetalle !== null && $model->avanzarProducto($idDetalle)) {
            $this->volver('Producto actualizado.');
        }

        $this->volver('No se pudo actualizar el producto.');
    }

// ==================================================================================================
    public function pagar()
    {
        $this->exigirPost();

        $idPedido = $this->leerId('idPedido');
        $metodo = $_POST['metodo'] ?? '';

        if (!in_array($metodo, self::METODOS_PAGO, true)) {
            $this->volver('Método de pago no válido.');
        }

        $model = new PedidoModel();

        try {
            if ($idPedido !== null && $model->pagarPedido($idPedido, $metodo)) {
                $this->volver("Pedido #$idPedido pagado y completado.");
            }
        } catch (PDOException $e) {
            // por ejemplo, ya existia un pago para ese pedido
            error_log($e->getMessage());
        }

        $this->volver('No se pudo registrar el pago.');
    }

// ==================================================================================================
// AYUDAS PRIVADAS
// ==================================================================================================

    // solo POST y con token CSRF valido; si no, vuelve al panel
    private function exigirPost($destino = '/PJF/empleado')
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $destino);
            exit;
        }

        if (!Csrf::validar()) {
            $this->volver('La sesión del formulario expiró. Intenta de nuevo.', $destino);
        }
    }

    private function leerId($campo)
    {
        $id = filter_input(INPUT_POST, $campo, FILTER_VALIDATE_INT);

        return ($id === false || $id === null || $id < 1) ? null : $id;
    }

    // guarda un mensaje y redirige (siempre termina la ejecucion)
    private function volver($mensaje, $destino = '/PJF/empleado')
    {
        $_SESSION['mensaje_empleado'] = $mensaje;
        header('Location: ' . $destino);
        exit;
    }
}
