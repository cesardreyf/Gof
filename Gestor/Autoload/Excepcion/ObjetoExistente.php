<?php

namespace Gof\Gestor\Autoload\Excepcion;

/**
 * Excepción lanzada cuando se intenta cargar una clase, interfaz o trait que ya existe
 *
 * @package Gof\Gestor\Autoload\Excepcion
 */
class ObjetoExistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombre Nombre de la clase, interfaz o trait
     */
    public function __construct(string $nombre)
    {
        parent::__construct("Ya existe una clase, interfaz o trait cargado con el mismo nombre: {$nombre}");
    }

}
