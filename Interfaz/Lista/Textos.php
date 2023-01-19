<?php

namespace Gof\Interfaz\Lista;

use Gof\Interfaz\Lista;

interface Textos extends Lista
{
    public function agregar(string $elemento): string;
}
