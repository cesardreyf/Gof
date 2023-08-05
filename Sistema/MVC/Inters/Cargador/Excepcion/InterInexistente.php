<?php

namespace Gof\Sistema\MVC\Inters\Cargador\Excepcion;

use Gof\Sistema\MVC\Inters\Contenedor\Contenedor;

/**
 * Excepción lanzada cuando el autoload no pudo cargar la clase o crear la instancia
 *
 * @package Gof\Sistema\MVC\Inters\Excepcion
 */
class InterInexistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $inter Nombre del inter.
     */
    public function __construct(string $inter)
    {
        parent::__construct("El inter '{$inter}' no existe o no es válido.");
    }

}
