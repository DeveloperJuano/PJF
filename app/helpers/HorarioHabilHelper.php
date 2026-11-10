<?php

class HorarioHabil
{
    // dias de atencion: 1 = lunes ... 7 = domingo
    const DIAS = [1, 2, 3, 4, 5, 6, 7];
    const APERTURA = '11:00';
    const CIERRE = '22:00';

    // una reserva ocupa la mesa este tiempo (minutos)
    const DURACION_RESERVA_MIN = 120;
    // la mesa se ve "reservada" desde este tiempo antes de la hora reservada
    const AVISO_RESERVA_MIN = 60;
    // el cliente puede pedir desde este tiempo antes de la hora de su reserva
    const ANTICIPO_PEDIDO_MIN = 15;

    // Devuelve null si el momento es valido, o el motivo si no.
    // $duracionMin: una reserva debe TERMINAR antes del cierre;
    // un pedido para llevar solo debe RECOGERSE antes del cierre.
    public static function validar(DateTime $momento, $duracionMin = 0)
    {
        if (!in_array((int) $momento->format('N'), self::DIAS, true)) {
            return 'Ese día el restaurante no atiende.';
        }

        $fecha = $momento->format('Y-m-d');
        $apertura = new DateTime($fecha . ' ' . self::APERTURA);
        $cierre = new DateTime($fecha . ' ' . self::CIERRE);

        $fin = (clone $momento)->modify('+' . (int) $duracionMin . ' minutes');

        if ($momento < $apertura || $fin > $cierre) {
            return 'El horario de atención es de ' . self::APERTURA . ' a ' . self::CIERRE . '.';
        }

        return null;
    }
}