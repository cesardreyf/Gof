<?php

namespace Gof\Gestor\Autoload\Excepcion;

/**
 * Excepción lanzada cuando no se encuentra la clase, interfaz o trait dentro del archivo
 *
 * Excepción que se lanza cuando el archivo existe, es cargado, y dentro no está la clase,
 * interfaz o trait solicitado.
 *
 * @package Gof\Gestor\Autoload\Excepcion
 */
class ObjetoInexistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $rutaDelArchivo  Ubicación del archivo
     * @param string $nombreDelObjeto Nombre de la clase, interfaz o trait
     */
    public function __construct(string $rutaDelArchivo, string $nombreDelObjeto)
    {
        parent::__construct("No se encontró '{$nombreDelObjeto}' en el archivo: {$rutaDelArchivo}");
    }

}
