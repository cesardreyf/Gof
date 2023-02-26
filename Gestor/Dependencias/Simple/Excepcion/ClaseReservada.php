<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando la clase ya está reservada por el gestor
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class ClaseReservada extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombre Nombre de la clase o interfaz ya reservada
     */
    public function __construct(string $nombre)
    {
        parent::__construct("La clase '{$nombre}' ya se encuentra reservada en el gestor");
    }

}
