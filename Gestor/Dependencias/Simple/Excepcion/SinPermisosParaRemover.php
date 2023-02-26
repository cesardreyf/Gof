<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando no se tienen permisos para remover la clase o interfaz reservada
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class SinPermisosParaRemover extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombre Nombre de la clase o interfaz
     */
    public function __construct(string $clase)
    {
        parent::__construct("No tienes permisos para remover la clase: {$clase}");
    }

}
