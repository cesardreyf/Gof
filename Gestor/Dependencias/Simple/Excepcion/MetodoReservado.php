<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando se intenta reservar un nombre de método ya reservado.
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class MetodoReservado extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $metodo Nombre del método.
     */
    public function __construct(string $metodo)
    {
        parent::__construct("El nombre del método: '{$metodo}' ya se encuentra reservado.");
    }

}
