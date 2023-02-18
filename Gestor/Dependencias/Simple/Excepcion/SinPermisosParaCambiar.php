<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

class SinPermisosParaCambiar extends Excepcion
{

    public function __construct(string $nombre)
    {
        parent::__construct("No tienes permisos para cambiar la instancia de la clase reservada: {$nombre}");
    }

}
