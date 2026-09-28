<?php

class MenuModel{
    private $productos;
    public function __construct()
        {
            $this->productos = [
                [
                    'nombre' => 'Plato especial',
                    'descripcion' => 'Descripción del plato.',
                    'precio' => 25000,
                    'imagen' => 'plato1.jpg',
                    'categoria' => 'plato-fuertes'
                ],
                [
                    'nombre' => 'Hamburguesa',
                    'descripcion' => 'Hamburguesa de la casa.',
                    'precio' => 30000,
                    'imagen' => 'plato2.jpg',
                    'categoria' => 'plato-fuertes'
                ],
                [
                    'nombre' => 'Postre especial',
                    'descripcion' => 'Postre de la casa.',
                    'precio' => 15000,
                    'imagen' => 'postre1.jpg',
                    'categoria' => 'postres' 
                ]
            ];
        }

        public function obtenerProducto(){

        return $this->productos;

        }

}

?>