<?php

namespace Gof\Sistema\MVC\Registros\Interfaz;

/**
 * Interfaz para los errores
 *
 * @package Gof\Sistema\MVC\Registros\Interfaz
 */
interface Error
{
    /**
     * Tipo de error
     *
     * @return int
     */
    public function tipo(): int;

    /**
     * Mensaje de error
     *
     * @return string
     */
    public function mensaje(): string;

    /**
     * Archivo que produjo el error
     *
     * @return string
     */
    public function archivo(): string;

    /**
     * Línea donde se produjo el error
     *
     * @return int
     */
    public function linea(): int;
}
