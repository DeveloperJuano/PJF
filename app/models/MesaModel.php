
<?php

class MesaModel
{
    private function inicializarMesas()
    {
        if (!isset($_SESSION['mesas'])) {
            $_SESSION['mesas'] = [
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

    public function obtenerMesas()
    {
        $this->inicializarMesas();

        return $_SESSION['mesas'];
    }

    public function crearMesa($numero, $capacidad)
    {
        $this->inicializarMesas();

        foreach ($_SESSION['mesas'] as $mesa) {
            if ($mesa['numero'] === $numero) {
                return false;
            }
        }

        $_SESSION['mesas'][] = [
            'numero' => $numero,
            'capacidad' => $capacidad,
            'estado' => 'disponible'
        ];

        return true;
    }
    
    public function actualizarCapacidad($numero, $capacidad)
    {
        $this->inicializarMesas();

        foreach ($_SESSION['mesas'] as &$mesa) {
            if ($mesa['numero'] === $numero) {
                $mesa['capacidad'] = $capacidad;

                unset($mesa);
                return true;
            }
        }

        unset($mesa);
        return false;
    }

    public function desactivarMesa($numero)
    {
        $this->inicializarMesas();

        foreach ($_SESSION['mesas'] as &$mesa) {
            if ($mesa['numero'] === $numero) {
                $mesa['estado'] = 'inactiva';

                unset($mesa);
                return true;
            }
        }

        unset($mesa);
        return false;
    }
    public function activarMesa($numero)
    {
        $this->inicializarMesas();

        foreach ($_SESSION['mesas'] as &$mesa) {
            if ($mesa['numero'] === $numero) {
                $mesa['estado'] = 'disponible';
                unset($mesa);
                return true;
            }
        }

        unset($mesa);
        return false;
    }
}

?>
