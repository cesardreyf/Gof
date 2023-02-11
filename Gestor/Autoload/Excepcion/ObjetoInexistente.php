<?php

namespace Gof\Gestor\Autoload\Excepcion;

class ObjetoInexistente extends Excepcion
{

    public function __construct(string $rutaDelArchivo, string $nombreDelObjeto)
    {
        parent::__construct("No se encontró '{$nombreDelObjeto}' en el archivo: {$rutaDelArchivo}");
    }

}
