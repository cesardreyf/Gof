<?php

namespace Gof\Sistema\Reportes\Interfaz;

interface Plantilla
{
    public function traducir($datos): bool;
    public function mensaje(): string;
}
