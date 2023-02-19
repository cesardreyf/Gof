<?php

namespace Gof\Gestor\Dependencias\Simple\Excepcion;

class DependenciasInexistentes extends Excepcion
{

    public function __construct(array $lista)
    {
        $lista = implode(', ', $lista);
        parent::__construct("Las siguientes clases no se encuentran reservadas en el gestor: {$lista}.");
    }

}
