<?php

namespace Gof\Sistema\MVC\Registros\Errores;

use Gof\Sistema\MVC\Registros\Interfaz\Error;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorTraducible;

/**
 * Traduce los datos de un error a un mensaje de texto
 *
 * @package Gof\Sistema\MVC\Registros
 */
class Traductor implements ErrorTraducible
{

    /**
     * @var array<int, string> Lista de tipos de errores
     */
    static protected array $tipoDeErrores = [
            E_ERROR             => 'E_ERROR'
        ,   E_WARNING           => 'E_WARNING'
        ,   E_PARSE             => 'E_PARSE'
        ,   E_NOTICE            => 'E_NOTICE'
        ,   E_CORE_ERROR        => 'E_CORE_ERROR'
        ,   E_CORE_WARNING      => 'E_CORE_WARNING'
        ,   E_COMPILE_ERROR     => 'E_COMPILE_ERROR'
        ,   E_COMPILE_WARNING   => 'E_COMPILE_WARNING'
        ,   E_USER_ERROR        => 'E_USER_ERROR'
        ,   E_USER_WARNING      => 'E_USER_WARNING'
        ,   E_USER_NOTICE       => 'E_USER_NOTICE'
        ,   E_STRICT            => 'E_STRICT'
        ,   E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR'
        ,   E_DEPRECATED        => 'E_DEPRECATED'
        ,   E_USER_DEPRECATED   => 'E_USER_DEPRECATED'
    ];

    /**
     * Traduce los datos de un Error a un string
     *
     * Convierte la información almacenada dentro de un objeto Error a un string.
     *
     * @return string Devuelve un string con toda la información del error.
     */
    public function traducir(Error $error): string
    {
        return date('(d/m/Y) [G:i:s]')                                          . "\n\n\t"
        .   'Tipo    ' . static::$tipoDeErrores[$error->tipo()]                 . "\n\t"
        .   'Linea   ' . $error->linea()                                        . "\n\t"
        .   'Archivo ' . $error->archivo()                                      . "\n\t"
        .   'Mensaje ' . str_replace("\n",   "\n\t        ", $error->mensaje()) . "\n\n";
    }

}
