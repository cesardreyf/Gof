<?php

namespace Gof\Contrato\Registro;

interface Registro
{
    public function registrar(string $mensaje): bool;
    public function volcar(): bool;
}
