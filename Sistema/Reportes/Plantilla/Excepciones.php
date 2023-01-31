<?php

namespace Gof\Sistema\Reportes\Plantilla;

use Throwable;
use Gof\Sistema\Reportes\Interfaz\Plantilla;

class Excepciones implements Plantilla
{
    private $mensaje = '';

    /**
     *  Convierte una excepción en un mensaje
     *
     *  Traduce una instancia de Exceptión a un mensaje guardable e imprimible
     *
     *  @param Exception $excepcion Instancia de la excepción a traducir
     *
     *  @return bool Devuelve TRUE si la traducción fue exitosa, FALSE de lo contrario
     */
    public function traducir($excepcion): bool
    {
        if( $excepcion instanceof Throwable ) {
            $this->mensaje = date('(d/m/Y) [G:i:s]')    . "\n\n\t"
            .   'Tipo      ' . get_class($excepcion)    . "\n\t"
            .   'Mensaje   ' . $excepcion->getMessage() . "\n\t"
            .   'Archivo   ' . $excepcion->getFile()    . "\n\t"
            .   'Linea     ' . $excepcion->getLine()    . "\n\t"
            .   'Codigo    ' . $excepcion->getCode()    . "\n\t"
            .   'Trace     ' . str_replace("\n", "\n\t          ", $excepcion->getTraceAsString()) . "\n\n";

            return true;
        }

        return false;
    }

    /**
     *  Mensaje traducido
     *
     *  @return string Devuelve el mensaje traducido de la excepción
     */
    public function mensaje(): string
    {
        return $this->mensaje;
    }

}
