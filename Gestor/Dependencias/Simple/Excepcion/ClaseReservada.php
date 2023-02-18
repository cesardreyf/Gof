<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

class ClaseReservada extends Excepcion
{

    public function __construct(string $nombre)
    {
        parent::__construct("La clase '{$nombre}' ya se encuentra reservada en el gestor");
    }

}
