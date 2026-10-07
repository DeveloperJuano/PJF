<?php

require __DIR__ ."/../models/MesaModel.php";
class ReservaController
{
    public function index()
    {
        $model = new MesaModel();
        $mesas = $model->obtenerMesas();
        require_once __DIR__ . '/../views/reserva/index.php';
        
    }
}

?>