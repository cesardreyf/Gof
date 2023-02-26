<?php

namespace Gof\Gestor\Autoload\Excepcion;

/**
 * Excepción lanzado cuando no se puede leer el archivo
 *
 * @package Gof\Gestor\Autoload\Excepcion
 */
class ArchivoInaccesible extends Excepcion
{

    /**
     * Constructor
     *
     * @param string $rutaDelArchivo Ubicación del archivo que no se puede leer
     */
    public function __construct(string $rutaDelArchivo)
    {
        parent::__construct("No se puede leer el archivo: {$rutaDelArchivo}");
    }

}
