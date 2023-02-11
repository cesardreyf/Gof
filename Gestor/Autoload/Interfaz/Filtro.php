<?php

namespace Gof\Gestor\Autoload\Interfaz;

interface Filtro
{
    public function clase(string $cadena): bool;
    public function espacioDeNombre(string $cadena): bool;
}
