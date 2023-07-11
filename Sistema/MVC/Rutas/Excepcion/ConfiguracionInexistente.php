<?php

namespace Gof\Sistema\MVC\Rutas\Excepcion;

/**
 * Excepción lanzada cuando no existe ninguna configuración definida
 *
 * Excepción lanzada cuando no se estableció la configuración
 * del gestor de rutas.
 *
 * @package Gof\Sistema\MVC\Rutas\Excepcion
 */
class ConfiguracionInexistente extends Excepcion
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct('La configuración del gestor de rutas no fue definido');
    }

}
