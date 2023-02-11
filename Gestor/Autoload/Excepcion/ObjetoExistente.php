<?php

namespace Gof\Gestor\Autoload\Excepcion;

class ObjetoExistente extends Excepcion
{

    public function __construct(string $nombre)
    {
        parent::__construct("Ya existe una clase, interfaz o trait cargado con el mismo nombre: {$nombre}");
    }

}
