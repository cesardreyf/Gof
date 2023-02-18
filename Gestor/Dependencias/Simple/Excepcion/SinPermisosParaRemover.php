<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

class SinPermisosParaRemover extends Excepcion
{

    public function __construct(string $clase)
    {
        parent::__construct("No tienes permisos para remover la clase: {$clase}");
    }

}
