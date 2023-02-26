<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando no se tienen permisos para cambiar el valor reservado para la clase o interfaz
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class SinPermisosParaCambiar extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombre Nombre de la clase o interfaz
     */
    public function __construct(string $nombre)
    {
        parent::__construct("No tienes permisos para cambiar la instancia de la clase reservada: {$nombre}");
    }

}
