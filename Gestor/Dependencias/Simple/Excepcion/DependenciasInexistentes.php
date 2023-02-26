<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

/**
 * Excepción lanzada cuando algunas clases o interfaces no se encontraron dentro del gestor
 *
 * Excepción empleada por el gestor cuando no se encuentran una o más clases dentro de la
 * lista interna del gestor.
 *
 * @package Gof\Gestor\Dependencias\Simple\Excepcion
 */
class DependenciasInexistentes extends Excepcion
{

    /**
     * Constructor
     *
     * @param string[] Lista de nombres de clases o interfaces
     */
    public function __construct(array $lista)
    {
        $lista = implode(', ', $lista);
        parent::__construct("Las siguientes clases no se encuentran reservadas en el gestor: {$lista}.");
    }

}
