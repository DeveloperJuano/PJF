<?php
require_once __DIR__ . '/../models/MesaModel.php';
class AdminMesaController
{
//==================================================================================================
    public function index()
    {
        $model = new MesaModel();
        $mesas = $model->obtenerMesas();
        
        require_once __DIR__ . '/../views/admin/mesas/index.php';
        }
//==================================================================================================
    public function crear()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/admin/mesas');
            exit;
        }

        $numero = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);
        $capacidad = filter_input(INPUT_POST, 'capacidad', FILTER_VALIDATE_INT);

        if ($numero === false || $numero === null || $numero < 1 ||
            $capacidad === false || $capacidad === null || $capacidad < 1) {
            $_SESSION['mensaje_mesas'] = 'Ingresa un número y una capacidad válidos.';
            header('Location: /PJF/admin/mesas');
            exit;
        }

        $model = new MesaModel();

        if ($model->crearMesa($numero, $capacidad)) {
            $_SESSION['mensaje_mesas'] = 'Mesa creada correctamente.';
        } else {
            $_SESSION['mensaje_mesas'] = 'Ya existe una mesa con ese número.';
        }

        header('Location: /PJF/admin/mesas');
        exit;
    }
//==================================================================================================
    public function actualizarCapacidad()
        {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: /PJF/admin/mesas');
                exit;
            }

            $numero = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);
            $capacidad = filter_input(INPUT_POST, 'capacidad', FILTER_VALIDATE_INT);

            if (
                $numero === false || $numero === null || $numero < 1 ||
                $capacidad === false || $capacidad === null || $capacidad < 1
            ) {
                $_SESSION['mensaje_mesas'] = 'Ingresa un número y una capacidad válidos.';
                header('Location: /PJF/admin/mesas');
                exit;
            }

            $model = new MesaModel();

            if ($model->actualizarCapacidad($numero, $capacidad)) {
                $_SESSION['mensaje_mesas'] = 'Capacidad actualizada correctamente.';
            } else {
                $_SESSION['mensaje_mesas'] = 'No se encontró la mesa seleccionada.';
            }

            header('Location: /PJF/admin/mesas');
            exit;
    }
//==================================================================================================
    public function desactivar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /PJF/admin/mesas');
            exit;
        }

        $numero = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);

        if ($numero === false || $numero === null || $numero < 1) {
            $_SESSION['mensaje_mesas'] = 'El número de mesa no es válido.';
            header('Location: /PJF/admin/mesas');
            exit;
        }

        $model = new MesaModel();

        if ($model->desactivarMesa($numero)) {
            $_SESSION['mensaje_mesas'] = 'Mesa desactivada correctamente.';
        } else {
            $_SESSION['mensaje_mesas'] = 'No se encontró la mesa seleccionada.';
        }

        header('Location: /PJF/admin/mesas');
        exit;
    }
    public function activar()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /PJF/admin/mesas');
        exit;
    }

    $numero = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);

    if ($numero === false || $numero === null || $numero < 1) {
        $_SESSION['mensaje_mesas'] = 'El número de mesa no es válido.';
        header('Location: /PJF/admin/mesas');
        exit;
    }

    $model = new MesaModel();

    if ($model->activarMesa($numero)) {
        $_SESSION['mensaje_mesas'] = 'Mesa activada correctamente.';
    } else {
        $_SESSION['mensaje_mesas'] = 'No se encontró la mesa seleccionada.';
    }

    header('Location: /PJF/admin/mesas');
    exit;
    }
//==================================================================================================
}
