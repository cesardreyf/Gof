<?php

namespace Gof\Sistema\MVC\Inters\Cargador\Excepcion;

use Gof\Sistema\MVC\Interfaz\Ejecutable;

/**
 * Excepción lanzada cuando la clase no implementa la interfaz esperada
 *
 * @package Gof\Sistema\MVC\Inters\Excepcion
 */
class InterInvalido extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombreDelInter Nombre completo de la clase del Inter.
     */
    public function __construct(string $nombreDelInter)
    {
        parent::__construct("El inter '{$nombreDelInter}' no implementa la interfaz: '" . Ejecutable::class . '\'.');
    }

}
