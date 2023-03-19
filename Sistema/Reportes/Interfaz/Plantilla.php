<?php

namespace Gof\Sistema\Reportes\Interfaz;

use Throwable;

/**
 * Interfaz empleada por el sistema de reportes para las plantillas
 *
 * @package Gof\Sistema\Reportes\Interfaz
 */
interface Plantilla
{
    /**
     * Traduce los datos
     *
     * @param mixed $datos Datos a traducir
     *
     * @return bool Devuelve **true** en caso de éxito o **false** de lo contrario
     */
    public function traducir(array|Throwable $datos): bool;

    /**
     * Mensaje traducido
     *
     * Mensaje traducido de los datos recibidos
     *
     * @return string Devuelve el mensaje traducido
     *
     * @see Plantilla::traducir()
     */
    public function mensaje(): string;
}
