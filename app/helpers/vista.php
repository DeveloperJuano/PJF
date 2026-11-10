<?php

/*
AYUDAS PARA LAS VISTAS
*/

// escapa texto para mostrarlo en HTML sin riesgo de XSS
if (!function_exists('e')) {
    function e($valor)
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

// 24000 => $24.000
if (!function_exists('dinero')) {
    function dinero($valor)
    {
        return '$' . number_format((float) $valor, 0, ',', '.');
    }
}

// segundos => minutos enteros (nunca negativos)
if (!function_exists('minutos')) {
    function minutos($segundos)
    {
        return intdiv(max(0, (int) $segundos), 60);
    }
}

// numero de mesa => "Mesa 3"; NULL (pedido sin mesa) => "Para llevar"
if (!function_exists('lugar')) {
    function lugar($mesa)
    {
        return ($mesa === null || $mesa === '') ? 'Para llevar' : 'Mesa ' . $mesa;
    }
}

// "2026-11-10 18:30:00" => "10/11 18:30"
if (!function_exists('fechaCorta')) {
    function fechaCorta($fechaHora)
    {
        return $fechaHora ? date('d/m H:i', strtotime($fechaHora)) : '';
    }
}