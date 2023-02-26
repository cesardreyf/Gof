<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando la clase agregada no existe
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class ClaseInexistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombre Nombre de la clase o interfaz
     */
    public function __construct(string $nombre)
    {
        parent::__construct("Se intentó agregar una clase inexistente: {$nombre}");
    }

}
