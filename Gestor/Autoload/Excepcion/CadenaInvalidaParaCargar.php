<?php

namespace Gof\Gestor\Autoload\Excepcion;

class CadenaInvalidaParaCargar extends Excepcion
{

    public function __construct(string $nombre)
    {
        parent::__construct("El nombre ingresado ({$nombre}) no pasó correctamente el filtro");
    }

}
