<?php

namespace Gof\Interfaz\Mensajes;

interface Guardable
{
    public function guardar(string $mensaje): bool;
}
