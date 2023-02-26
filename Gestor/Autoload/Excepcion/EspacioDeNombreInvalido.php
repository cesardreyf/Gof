<?php

namespace Gof\Gestor\Autoload\Excepcion;

/**
 * Excepción lanzada cuando el espacio de nombre no es válido
 *
 * Excepción que se lanza cuando el nombre del espacio de nombre no pasa el filtro del gestor.
 *
 * @package Gof\Gestor\Autoload\Excepcion
 */
class EspacioDeNombreInvalido extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $espacioDeNombre Espacio de nombre
     */
    public function __construct(string $espacioDeNombre)
    {
        parent::__construct("El espacio de nombre no cumple con los requisitos del filtro. (Namespace: {$espacioDeNombre})");
    }

}
