<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

class ObjetoNoCorrespondido extends Excepcion
{

    public function __construct(string $clase, $objeto)
    {
        parent::__construct("No se puede asignar a la clase '{$clase}' el tipo: " . gettype($objeto));
    }

}
