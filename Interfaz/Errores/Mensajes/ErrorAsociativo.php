<?php

namespace Gof\Interfaz\Errores\Mensajes;

/**
 * Interfaz para mensajes de errores asociativos
 *
 * @package Gof\Interfaz\Errores\Mensajes
 */
interface ErrorAsociativo extends Error
{
    /**
     * Especifica la clave a emplear
     *
     * Especifica la clave que será empleada para definir u obtener los códigos
     * o mensajes de errores.
     *
     * @return bool Devuelve **true** si ya existen errores con la clave.
     */
    public function clave(string $clave): bool;
}
