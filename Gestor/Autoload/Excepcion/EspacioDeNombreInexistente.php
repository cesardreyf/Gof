<?php

namespace Gof\Gestor\Autoload\Excepcion;

class EspacioDeNombreInexistente extends Excepcion
{

    public function __construct(string $espacioDeNombre, string $nombre)
    {
        parent::__construct("No se pudo cargar '{$nombre}' porque el espacio de nombre '{$espacioDeNombre}' no existe en la lista de espacios de nombres reservados.");
    }

}
