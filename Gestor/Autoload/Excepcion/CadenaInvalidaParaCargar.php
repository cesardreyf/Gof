<?php

namespace Gof\Gestor\Autoload\Excepcion;

/**
 * Excepción lanzada cuando el nombre ingresado no es válido para ser cargado
 *
 * Excepción que se lanza cuando la cadena ingresada como nombre no pasa el filtro del gestor.
 *
 * @package Gof\Gestor\Autoload\Excepcion
 */
class CadenaInvalidaParaCargar extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $nombre Nombre inválido
     */
    public function __construct(string $nombre)
    {
        parent::__construct("El nombre ingresado ({$nombre}) no pasó correctamente el filtro");
    }

}
