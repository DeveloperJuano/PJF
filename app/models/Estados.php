<?php

/*
ESTADOS DEL PEDIDO (columna pedido.estado)
1 por confirmar -> 2 en preparacion -> 3 por pagar -> 4 completado
-1 cancelado (se puede cancelar mientras este en 1 o 2)
*/
class EstadoPedido
{
    const POR_CONFIRMAR = 1;
    const EN_PREPARACION = 2;
    const POR_PAGAR = 3;
    const COMPLETADO = 4;
    const CANCELADO = -1;

    public static function etiqueta($estado)
    {
        return match ((int) $estado) {
            self::POR_CONFIRMAR => 'Por confirmar',
            self::EN_PREPARACION => 'En preparación',
            self::POR_PAGAR => 'Por pagar',
            self::COMPLETADO => 'Completado',
            self::CANCELADO => 'Cancelado',
            default => 'Desconocido',
        };
    }
}

/*
ESTADOS DEL PRODUCTO (columna detallePedido.estado)
1 por confirmar -> 2 en cola -> 3 sirviendo -> 4 servido
-1 rechazado
*/
class EstadoProducto
{
    const POR_CONFIRMAR = 1;
    const EN_COLA = 2;
    const SIRVIENDO = 3;
    const SERVIDO = 4;
    const RECHAZADO = -1;

    public static function etiqueta($estado)
    {
        return match ((int) $estado) {
            self::POR_CONFIRMAR => 'Por confirmar',
            self::EN_COLA => 'En cola',
            self::SIRVIENDO => 'Sirviendo',
            self::SERVIDO => 'Servido',
            self::RECHAZADO => 'Rechazado',
            default => 'Desconocido',
        };
    }
}
