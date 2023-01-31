<?php

namespace Gof\Sistema\Reportes\Interfaz;

interface Reportero
{
    public function reportar($datos): bool;
    public function plantilla(): Plantilla;
    public function imprimir(): bool;
}
