<?php
class MesaModel
{
    public function obtenerMesas()
    {
        return [
            [
                'numero' => 1,
                'capacidad' => 2,
                'estado' => 'disponible'
            ],
            [
                'numero' => 2,
                'capacidad' => 4,
                'estado' => 'disponible'
            ],
            [
                'numero' => 3,
                'capacidad' => 3,
                'estado' => 'ocupada'
            ],
            [
                'numero' => 4,
                'capacidad' => 9,
                'estado' => 'disponible'
            ]
        ];
    }
}


?>