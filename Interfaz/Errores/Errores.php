<?php

namespace Gof\Interfaz\Errores;

interface Errores
{
    public function hay(): bool;
    public function error(): int;
    public function errores(): array;
}
