<?php

namespace Gof\Sistema\MVC\Aplicacion\DAP;

use Closure;

/**
 * Datos de Acceso Público de nivel 2
 *
 * @package Gof\Sistema\MVC\Aplicacion\DAP
 */
class N2 extends N3
{

    /**
     * Constructor
     *
     * @param N1 &$dap   Referencia al dap de nivel 1
     * @param N3 &$dapn3 Referencia al dap de nivel 3
     */
    public function __construct(public N1 &$dap, N3 &$dapn3)
    {
        parent::__construct();

        Closure::bind(function() use (&$propiedadesDelDapN3) {
            foreach( $this as $propiedad => &$valor ) {
                $propiedadesDelDapN3[$propiedad] =& $valor;
            }
        }, $dapn3, N3::class)();

        foreach( $propiedadesDelDapN3 as $propiedad => &$valor ) {
            $this->{$propiedad} =& $valor;
        }
    }

}
