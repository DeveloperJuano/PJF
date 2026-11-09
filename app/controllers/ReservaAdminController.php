<?php

require_once __DIR__ . '/../models/ReservaModel.php';

class ReservaAdminController
{
    // ==========================================================
    // MOSTRAR RESERVAS
    // ==========================================================

    public function index()
    {
        // VALIDAR QUE EL USUARIO TENGA SESIÓN
        if (!isset($_SESSION['idUsuario'], $_SESSION['rol'])) {
            header('Location: /PJF');
            exit;
        }

        // VALIDAR ROL
        if (!in_array($_SESSION['rol'], ['admin', 'empleado'], true)) {
            http_response_code(403);
            exit('Acceso denegado');
        }
        // SELECTOR DE RESERVAS TODO/PROXIMAS/PASADAS
        $categoria = $_GET['categoria'] ?? 'proximas';
        // OBTENER RESERVAS
        $model = new ReservaModel();
        switch ($categoria) {

            case 'pasadas':
                $reservas = $model->obtenerReservasPasadas();
                break;

            case 'todas':
                $reservas = $model->obtenerReservas();
                break;

            default:
                $categoria = 'proximas';
                $reservas = $model->obtenerReservasProximas();
                break;
        }

        // CARGAR VISTA COMPARTIDA
        require_once __DIR__ . '/../views/reserva/administrar.php';
    }
    //=============================================================
    // COFIRMAR
    public function confirmar(){
        // VALIDAR METODO HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/reserva/administrar');
            exit;
        }

        // VALIDAR SESION Y ROL
        if (
            !isset($_SESSION['idUsuario'], $_SESSION['rol']) ||
            !in_array($_SESSION['rol'], ['admin', 'empleado'], true)
        ) {
            http_response_code(403);
            exit('Acceso denegado');
        }

        // OBTENER ID DE LA RESERV
        $idReserva = filter_var(
            $_POST['idReserva'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($idReserva === false || $idReserva === null || $idReserva <= 0) {
            $_SESSION['mensaje_admin_reserva'] = 'Reserva no válida.';
            header('Location: /PJF/reserva/administrar');
            exit;
        }

        // CONFIRMAR RESERVA
        $model = new ReservaModel();

        $resultado = $model->confirmarReserva($idReserva);

        $_SESSION['mensaje_admin_reserva'] = $resultado
            ? 'Reserva confirmada correctamente.'
            : 'No se pudo confirmar la reserva.';

        header('Location: /PJF/reserva/administrar');
        exit;
    }
    // CANCERLAR RESERVA
    public function cancelar()
    {
        // VALIDAR METODO HTTP
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/reserva/administrar');
            exit;
        }

        // VALIDAR SESION Y ROL
        if (
            !isset($_SESSION['idUsuario'], $_SESSION['rol']) ||
            !in_array($_SESSION['rol'], ['admin', 'empleado'], true)
        ) {
            http_response_code(403);
            exit('Acceso denegado');
        }

        // OBTENER ID DE LA RESERVA
        $idReserva = filter_var(
            $_POST['idReserva'] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($idReserva === false || $idReserva === null || $idReserva <= 0) {
            $_SESSION['mensaje_admin_reserva'] = 'Reserva no válida.';
            header('Location: /PJF/reserva/administrar');
            exit;
        }

        // CANCELAR RESERVA
        $model = new ReservaModel();

        $resultado = $model->cancelarReserva($idReserva);

        $_SESSION['mensaje_admin_reserva'] = $resultado
            ? 'Reserva cancelada correctamente.'
            : 'No se pudo cancelar la reserva.';

        header('Location: /PJF/reserva/administrar');
        exit;
    }
}