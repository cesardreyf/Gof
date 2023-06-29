<?php

namespace Gof\Sistema\MVC\Registros\Interfaz;

use Throwable;

/**
 * Interfaz para el traductor de excepción a mensaje (string)
 *
 * @package Gof\Sistema\MVC\Registros\Interfaz
 */
interface ExcepcionTraducible
{
    /**
     * Traduce la excepción a un mensaje (string)
     *
     * @param Throwable $excepcion Excepción a traducir.
     *
     * @param string Devuelve el mensaje traducido de la excepción.
     */
    public function traducir(Throwable $excepcion): string;
}
