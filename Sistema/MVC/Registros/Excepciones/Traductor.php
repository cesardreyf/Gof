<?php

namespace Gof\Sistema\MVC\Registros\Excepciones;

use Gof\Sistema\MVC\Registros\Interfaz\ExcepcionTraducible;
use Throwable;

/**
 * Traduce los datos de una excepción a un mensaje de texto
 *
 * @package Gof\Sistema\MVC\Registros\Excepciones
 */
class Traductor implements ExcepcionTraducible
{

    /**
     * Traduce los datos de un Throwable a un string
     *
     * Convierte la información almacenada dentro de un objeto Throwable a un string.
     *
     * @return string Devuelve un string con toda la información de la execpción.
     */
    public function traducir(Throwable $excepcion): string
    {
        return date('(d/m/Y) [G:i:s]')              . "\n\n\t"
        .   'Tipo      ' . get_class($excepcion)    . "\n\t"
        .   'Mensaje   ' . $excepcion->getMessage() . "\n\t"
        .   'Archivo   ' . $excepcion->getFile()    . "\n\t"
        .   'Linea     ' . $excepcion->getLine()    . "\n\t"
        .   'Codigo    ' . $excepcion->getCode()    . "\n\t"
        .   'Trace     ' . str_replace("\n", "\n\t          ", $excepcion->getTraceAsString()) . "\n\n";
    }

}
