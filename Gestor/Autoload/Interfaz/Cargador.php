<?php

namespace Gof\Gestor\Autoload\Interfaz;

interface Cargador
{
    public function error(): int;
    public function cargar(string $ruta): bool;
}
