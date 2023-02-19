<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

class SinPermisosParaDefinir extends Excepcion
{

    public function __construct(string $clase)
    {
        parent::__construct("No tienes permisos para definir la instancia de la clase {$clase}");
    }

}
