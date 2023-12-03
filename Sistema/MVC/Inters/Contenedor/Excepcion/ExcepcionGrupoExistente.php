<?php

namespace Gof\Sistema\MVC\Inters\Contenedor\Excepcion;

use Gof\Sistema\MVC\Inters\Excepcion\Excepcion;

/**
 * Excepción lanzado cuando se intenta crear un grupo con un nombre ya existente
 *
 * @package Gof\Sistema\MVC\Inters\Contenedor\Excepcion
 */
class ExcepcionGrupoExistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $grupo Nombre del grupo
     */
    public function __construct(string $grupo)
    {
        parent::__construct("Ya existe un grupo llamado '$grupo'.");
    }

}
