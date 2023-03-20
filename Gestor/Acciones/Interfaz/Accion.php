<?php

namespace Gof\Gestor\Acciones\Interfaz;

interface Accion
{
    public function accionar(string $identificador, mixed $elemento);
}
