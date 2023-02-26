<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando la clase existe pero no fue reservada dentro del gestor
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class ClaseNoReservada extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombre Nombre de la clase o interfaz
     */
    public function __construct(string $nombre)
    {
        parent::__construct("La clase {$nombre} no está reservada en el gestor");
    }

}
