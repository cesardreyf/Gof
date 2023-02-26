<?php

namespace Gof\Gestor\Autoload\Excepcion;

/**
 * Excepción lanzada cuando el archivo no existe
 *
 * @package Gof\Gestor\Autoload\Excepcion
 */
class ArchivoInexistente extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $rutaDelArchivo Ubicación del archivo
     */
    public function __construct(string $rutaDelArchivo)
    {
        parent::__construct("No existe el archivo: {$rutaDelArchivo}");
    }

}
