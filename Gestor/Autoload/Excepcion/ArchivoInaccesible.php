<?php

namespace Gof\Gestor\Autoload\Excepcion;

class ArchivoInaccesible extends Excepcion
{

    public function __construct(string $rutaDelArchivo)
    {
        parent::__construct("No se puede leer el archivo: {$rutaDelArchivo}");
    }

}
