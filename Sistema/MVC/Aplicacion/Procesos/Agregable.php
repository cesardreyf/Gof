<?php

namespace Gof\Sistema\MVC\Aplicacion\Procesos;

use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Módulo de procesos agregables
 *
 * Este módulo solo permite agregar procesos a una proridad preestablecida.
 *
 * @package Gof\Sistema\MVC\Aplicacion\Procesos
 */
class Agregable
{
    /**
     * Referencia al array donde se agregarán los procesos
     *
     * @var array
     */
    private array $procesos;

    /**
     * Constructor
     *
     * @param array &$lp Referencia a la lista de procesos
     */
    public function __construct(array &$lp)
    {
        $this->procesos =& $lp;
    }

    public function agregar(Ejecutable $proceso)
    {
        $this->procesos[] = $proceso;
    }

}
