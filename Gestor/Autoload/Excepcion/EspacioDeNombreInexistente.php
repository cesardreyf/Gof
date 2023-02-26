<?php

namespace Gof\Gestor\Autoload\Excepcion;

/**
 * Excepción lanzada cuando el espacio de nombre no está reservado por el gestor
 *
 * Excepción lanzada cuando se intenta cargar una clase, interfaz o trait con un espacio de
 * nombre que no está reservado en el gestor.
 *
 * @package Gof\Gestor\Autoload\Excepcion
 */
class EspacioDeNombreInexistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $espacioDeNombre Espacio de nombre.
     * @param string $nombre          Nombre de la clase, interfaz o trait.
     */
    public function __construct(string $espacioDeNombre, string $nombre)
    {
        parent::__construct("No se pudo cargar '{$nombre}' porque el espacio de nombre '{$espacioDeNombre}' no existe en la lista de espacios de nombres reservados.");
    }

}
