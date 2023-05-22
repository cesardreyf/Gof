<?php

namespace Gof\Interfaz\Errores\Mensajes;

use Gof\Interfaz\Lista;

interface Error extends Lista
{
    public function hay(): bool;
    public function codigo(?int $error = null): int;
    public function mensaje(?string $error = null): string;
}
