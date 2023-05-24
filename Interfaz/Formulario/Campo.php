<?php

namespace Gof\Interfaz\Formulario;

use Gof\Interfaz\Errores\Mensajes\Error;

interface Campo
{
    public function tipo(): int;
    public function error(): Error;
    public function valor(): mixed;
    public function clave(): string;
}
