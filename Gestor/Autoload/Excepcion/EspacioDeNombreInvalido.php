<?php

namespace Gof\Gestor\Autoload\Excepcion;

class EspacioDeNombreInvalido extends Excepcion
{

    public function __construct(string $espacioDeNombre)
    {
        parent::__construct("El espacio de nombre no cumple con los requisitos del filtro. (Namespace: {$espacioDeNombre})");
    }

}
