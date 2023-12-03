<?php

namespace Gof\Sistema\MVC\Inters\Contenedor\Excepcion;

use Gof\Sistema\MVC\Inters\Excepcion\Excepcion;

/**
 * Excepción lanzado cuando se solicita información de un grupo de inters inexistente
 *
 * @package Gof\Sistema\MVC\Inters\Contenedor\Excepcion
 */
class ExcepcionGrupoInexistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $grupo Nombre del grupo
     */
    public function __construct(string $grupo)
    {
        parent::__construct("No existe ningún grupo llamado '$grupo'.");
    }

}
