<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

class ClaseInexistente extends Excepcion
{

    public function __construct(string $nombre)
    {
        parent::__construct("Se intentó agregar una clase inexistente: {$nombre}");
    }

}
