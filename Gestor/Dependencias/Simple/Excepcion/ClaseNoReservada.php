<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

class ClaseNoReservada extends Excepcion
{

    public function __construct(string $nombre)
    {
        parent::__construct("La clase {$nombre} no está reservada en el gestor");
    }

}
