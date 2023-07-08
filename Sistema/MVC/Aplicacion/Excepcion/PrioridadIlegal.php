<?php

namespace Gof\Sistema\MVC\Aplicacion\Excepcion;

use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;

/**
 * Excepción lanzada cuando se intenta agregar un proceso con una prioridad al que no se tiene acceso.
 *
 * @package Gof\Sistema\MVC\Aplicacion\Excepcion
 */
class PrioridadIlegal extends Excepcion
{

    /**
     * Constructor
     *
     * @param Prioridad $prioridad Prioridad al que no se tiene acceso.
     */
    public function __construct(Prioridad $prioridad)
    {
        parent::__construct("No se puede asignar al proceso la prioridad: {$prioridad->name}(#{$prioridad->value})");
    }

}
