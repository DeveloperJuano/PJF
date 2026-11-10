<?php

require __DIR__ ."/../models/MesaModel.php";
require_once __DIR__ . '/../models/ReservaModel.php';
require_once __DIR__ . '/../helpers/HorarioHabilHelper.php';

class ReservaController
{
    public function index()
    {
        $model = new MesaModel();
        $mesas = $model->obtenerMesas();
        require_once __DIR__ . '/../views/reserva/index.php';
        
    }
    // GUARDAR RESERVA
    public function guardar()
    {
        // VALIDAR PETICIÓN
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/reserva');
            exit;
        }

        // VALIDAR SESIÓN
        if (!isset($_SESSION['idUsuario'])) {
            $_SESSION['mensaje_reserva'] = 'Debes iniciar sesión para reservar.';

            header('Location: /PJF/reserva');
            exit;
        }

        // RECIBIR DATOS
        $idUsuario = (int) $_SESSION['idUsuario'];
        $idMesa = (int) ($_POST['idMesa'] ?? 0);
        $personas = (int) ($_POST['personas'] ?? 0);
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        

        // VALIDAR DATOS
        if ($idMesa <= 0 || $personas <= 0 || $fecha === '' || $hora === '') {
            $_SESSION['mensaje_reserva'] = 'Completa correctamente los datos.';

            header('Location: /PJF/reserva');
            exit;
        }

        // VALIDAR FECHA Y HORA
        $fechaHora = DateTime::createFromFormat(
            '!Y-m-d H:i',
            $fecha . ' ' . $hora
        );

        if (
            !$fechaHora ||
            $fechaHora->format('Y-m-d H:i') !== $fecha . ' ' . $hora ||
            $fechaHora <= new DateTime()
        ) {
            $_SESSION['mensaje_reserva'] = 'Selecciona una fecha y hora futuras válidas.';

            header('Location: /PJF/reserva');
            exit;
        }

        // VALIDAR HORARIO HABIL (la reserva debe terminar antes del cierre)
        $motivo = HorarioHabil::validar($fechaHora, HorarioHabil::DURACION_RESERVA_MIN);

        if ($motivo !== null) {
            $_SESSION['mensaje_reserva'] = $motivo;

            header('Location: /PJF/reserva');
            exit;
        }

        // REGISTRAR RESERVA
        try {
            $model = new ReservaModel();

            $resultado = $model->registrarReserva(
                $idUsuario,
                $idMesa,
                $fecha,
                $hora,
                $personas
            );

            if ($resultado) {
                $_SESSION['mensaje_reserva'] = 'Reserva registrada correctamente.';
            } else {
                $_SESSION['mensaje_reserva'] = 'La mesa no está disponible para ese horario.';
            }

        } catch (Throwable $e) {
            error_log($e->getMessage());

            $_SESSION['mensaje_reserva'] = 'Ocurrió un error al registrar la reserva.';
        }

        header('Location: /PJF/reserva');
        exit;
    }

}

