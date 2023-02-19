<?php

namespace Gof\Contrato\Dependencias;

use Gof\Interfaz\Errores\Errores;

interface Dependencias
{
    public function agregar(string $nombre, callable $invocador): bool;
    public function cambiar(string $nombre, object $instancia): bool;
    public function definir(string $nombre, object $instancia): bool;
    public function obtener(string $nombre): ?object;
    public function remover(string $nombre): bool;
    public function errores(): Errores;
}
