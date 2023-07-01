<?php

namespace Gof\Sistema\MVC\Registros\Interfaz;

use Throwable;

/**
 * Interfaz para los gestores de guardado de excepciones
 *
 * @package Gof\Sistema\MVC\Registros\Interfaz
 */
interface ExcepcionGuardable
{
    /**
     * Guarda la excepción
     *
     * @param Throwable $excepcion Excepción a guardar.
     *
     * @return bool Devuelve el estado de la persistencia.
     */
    public function guardar(Throwable $excepcion): bool;
}
