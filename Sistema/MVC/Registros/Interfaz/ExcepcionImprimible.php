<?php

namespace Gof\Sistema\MVC\Registros\Interfaz;

use Throwable;

/**
 * Interfaz para los gestores de impresión de excepciones
 *
 * @package Gof\Sistema\MVC\Registros\Interfaz
 */
interface ExcepcionImprimible
{
    /**
     * Imprime la excepción
     *
     * @param Throwable $excepcion Excepción a imprimir
     */
    public function imprimir(Throwable $excepcion);
}
