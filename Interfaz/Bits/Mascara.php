<?php

namespace Gof\Interfaz\Bits;

interface Mascara
{
    public function obtener(): int;
    public function definir(int $valor): int;
    public function activar(int $bits): int;
    public function desactivar(int $bits): int;
    public function activados(int $bits): bool;
    public function desactivados(int $bits): bool;
}
