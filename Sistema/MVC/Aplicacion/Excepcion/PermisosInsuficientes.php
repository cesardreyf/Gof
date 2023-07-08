<?php

namespace Gof\Sistema\MVC\Aplicacion\Excepcion;

use Gof\Sistema\MVC\Aplicacion\Procesos\Prioridad;

/**
 * Excepción lanzada cuando se intenta crear un módulo de procesos con prioridades al que no se tienen acceso
 *
 * @package Gof\Sistema\MVC\Aplicacion\Excepcion
 */
class PermisosInsuficientes extends Excepcion
{

    /**
     * Constructor
     *
     * @param Prioridad[] Lista de prioridades a las que se tienen acceso
     */
    public function __construct(array $prioridades)
    {
        $lista = implode(', ', array_map(
            function(Prioridad $prioridad) {
                return $prioridad->name;
            }
        , $prioridades));

        parent::__construct("Solo tienes permisos para crear módulos de procesos agregables con las siguientes prioridades: {$lista}");
    }

}
