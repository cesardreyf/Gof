<?php

namespace Gof\Sistema\MVC\Rutas\Excepcion;

/**
 * Excepción lanzada cuando no existe ningún enrutador
 *
 * Excepción lanzada por el gestor de rutas cuando no existe
 * ningún enrutador registrado.
 *
 * @package Gof\Sistema\MVC\Rutas\Excepcion
 */
class EnrutadorInexistente extends Excepcion
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('No existe ningún enrutador registrado');
    }

}
