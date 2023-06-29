<?php

namespace Gof\Sistema\MVC\Registros\Interfaz;

/**
 * Interfaz para los traductores de errores
 *
 * @package Gof\Sistema\MVC\Registros\Interfaz
 */
interface ErrorTraducible
{
    /**
     * Traduce la información de un Error a string
     *
     * @param Error $error Error a traducir.
     *
     * @return string Error traducido.
     */
    public function traducir(Error $error): string;
}
