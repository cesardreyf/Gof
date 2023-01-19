<?php

namespace Gof\Interfaz\Lista;

use Gof\Interfaz\Lista;

interface Enteros extends Lista
{
    public function agregar(int $elemento): int;
}
