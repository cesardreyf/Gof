<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando no se tienen permisos para definir el valor reservado para la clase o interfaz
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class SinPermisosParaDefinir extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombre Nombre de la clase o interfaz
     */
    public function __construct(string $clase)
    {
        parent::__construct("No tienes permisos para definir la instancia de la clase {$clase}");
    }

}
