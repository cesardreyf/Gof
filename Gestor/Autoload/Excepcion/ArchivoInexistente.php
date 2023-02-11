<?php

namespace Gof\Gestor\Autoload\Excepcion;

class ArchivoInexistente extends Excepcion
{

    public function __construct(string $rutaDelArchivo)
    {
        parent::__construct("No existe el archivo: {$rutaDelArchivo}");
    }

}
