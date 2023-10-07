<?php

namespace Gof\Gestor\Solicitud\Excepcion;

/**
 * Excepción lanzado cuando se obtiene un valor de un tipo inesperado
 *
 * @package Gof\Gestor\Solicitud\Excepcion
 */
class ErrorDeTipo extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $clave
     * @param string $tipo
     * @param string $metodo
     */
    public function __construct(string $clave, string $tipo, string $metodo)
    {
        $this->message = "Se esperaba un valor de tipo {$tipo} para la clave '{$clave}' del método {$metodo}";
    }

}
