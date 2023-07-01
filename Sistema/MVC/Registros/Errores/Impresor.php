<?php

namespace Gof\Sistema\MVC\Registros\Errores;

use Gof\Sistema\MVC\Registros\Interfaz\Error;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorImprimible;
use Gof\Sistema\MVC\Registros\Interfaz\ErrorTraducible;

/**
 * Gestor de impresión simple
 *
 * Imprime en pantalla el error de la forma más vaga y simple posible.
 *
 * @package Gof\Sistema\MVC\Registros\Errores
 */
class Impresor implements ErrorImprimible
{
    /**
     * @var ErrorTraducible Traductor de errores a string
     */
    private ErrorTraducible $traductor;

    /**
     * Constructor
     *
     * @param ErrorTraducible $traductor Traductor de errores a mensaje (string)
     */
    public function __construct(ErrorTraducible $traductor)
    {
        $this->traductor = $traductor;
    }

    /**
     * Imprime el error
     */
    public function imprimir(Error $error)
    {
        echo '<pre>'.$this->traductor->traducir($error).'</pre>';
    }

}
