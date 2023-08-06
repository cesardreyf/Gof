<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando se intenta acceder a un método inexistente
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class MetodoInexistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $metodo Nombre del método.
     */
    public function __construct(string $metodo)
    {
        parent::__construct("El método '{$metodo}' no existe o no está asociado a ninguna dependencia.");
    }

}
