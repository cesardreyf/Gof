<?php

namespace Gof\Sistema\MVC\Registros\Excepciones;

use Gof\Sistema\MVC\Registros\Interfaz\ExcepcionImprimible;
use Gof\Sistema\MVC\Registros\Interfaz\ExcepcionTraducible;
use Throwable;

/**
 * Gestor de impresión simple
 *
 * Imprime en pantalla la excepción.
 *
 * @package Gof\Sistema\MVC\Registros\Excepciones
 */
class Impresor implements ExcepcionImprimible
{
    /**
     * @var ExcepcionTraducible Traductor de errores a string
     */
    private ExcepcionTraducible $traductor;

    /**
     * Constructor
     *
     * @param ExcepcionTraducible $traductor Traductor de errores a mensaje (string)
     */
    public function __construct(ExcepcionTraducible $traductor)
    {
        $this->traductor = $traductor;
    }

    /**
     * Imprime el error
     */
    public function imprimir(Throwable $excepcion)
    {
        echo '<pre>'.$this->traductor->traducir($excepcion).'</pre>';
    }

}
